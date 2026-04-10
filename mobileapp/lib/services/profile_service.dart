import 'dart:io';
import 'dart:convert';
import 'package:flutter_dotenv/flutter_dotenv.dart';
import 'package:flutter/foundation.dart';
import 'package:supabase_flutter/supabase_flutter.dart';
import 'laravel_service.dart';

class ProfileService {
  static final ProfileService _instance = ProfileService._internal();
  factory ProfileService() => _instance;
  ProfileService._internal();

  final SupabaseClient _client = Supabase.instance.client;
  final _baseUrl = dotenv.env['BACKEND_URL'] ?? "https://chorale.onrender.com";

  // Cache to store the profile data including the integer ID
  static Map<String, dynamic>? _cachedProfile;

  Future<Map<String, dynamic>?> fetchProfile({bool forceRefresh = false}) async {
    // 1. Check local memory cache
    if (!forceRefresh && _cachedProfile != null) {
      return _cachedProfile;
    }

    // 2. Check LaravelService memory cache (from syncSession)
    if (!forceRefresh && LaravelService().cachedUser != null) {
      _cachedProfile = LaravelService().cachedUser;
      return _cachedProfile;
    }

    final user = _client.auth.currentUser;
    if (user == null) {
      debugPrint("ProfileService: No Supabase user found.");
      return null;
    }

    if (forceRefresh) {
      _cachedProfile = null;
      debugPrint("ProfileService: Forcing refresh of profile data...");
    }

    Map<String, dynamic>? profileData;

    // 3. Attempt Laravel API
    try {
      debugPrint("ProfileService: Syncing and fetching from Laravel...");
      bool synced = await LaravelService().syncSession();
      if (!synced) {
        throw Exception("Échec de synchronisation de la session (Vérifiez les logs réseau ou CORS)");
      }

      // Check if syncSession populated the cache
      if (!forceRefresh && LaravelService().cachedUser != null) {
        profileData = LaravelService().cachedUser;
      } else {
        final response = await LaravelService().get('$_baseUrl/api/profile');
        if (response.statusCode == 200) {
          final decoded = jsonDecode(response.body);
          if (decoded['status'] == 'success' && decoded['user'] != null) {
            profileData = Map<String, dynamic>.from(decoded['user']);
          }
        }
      }
    } catch (e) {
      debugPrint("ProfileService: Laravel fetch error: $e");
      // If we have a cached version even if stale, we might return it as fallback if not forceRefresh
      if (_cachedProfile != null) return _cachedProfile;
      throw Exception("Défaut bloquant connexion Web/Base : $e");
    }

    if (profileData != null && profileData!.isNotEmpty) {
      _cachedProfile = profileData;
      return _cachedProfile;
    }

    throw Exception("Données utilisateur introuvables sur le serveur principal.");
  }

  /// Helper to get only the integer ID of the current user
  Future<int?> getIntegerUserId() async {
    // Synchronous check if possible
    if (_cachedProfile != null && _cachedProfile!['id'] != null) {
      return int.tryParse(_cachedProfile!['id'].toString());
    }
    
    if (LaravelService().cachedUser != null && LaravelService().cachedUser!['id'] != null) {
      return int.tryParse(LaravelService().cachedUser!['id'].toString());
    }

    final profile = await fetchProfile();
    if (profile != null && profile['id'] != null) {
      return int.tryParse(profile['id'].toString());
    }
    return null;
  }

  Future<void> updateProfile(Map<String, dynamic> updates) async {
    final user = _client.auth.currentUser;
    if (user == null) throw Exception("Utilisateur non connecté");

    // 1. Sync to Laravel FIRST
    try {
      debugPrint("ProfileService: Updating Laravel profile...");
      final response = await LaravelService().updateProfile(updates);
      
      // If LaravelService.updateProfile doesn't throw but returns a response, 
      // we check for success if applicable. (Assuming it throws on 4xx/5xx)
      
      // Invalidate cache to force reload on next fetch
      _cachedProfile = null;
    } catch (e) {
      debugPrint("ProfileService: updateProfile Laravel error: $e");
      throw Exception("Échec de la mise à jour sur le serveur principal (Laravel).");
    }

    // 2. ONLY if Laravel succeeded, update Supabase directly as backup
    try {
      debugPrint("ProfileService: Updating Supabase backup...");
      final response = await _client
          .from('users')
          .update(updates)
          .eq('email', user.email!)
          .select();
          
      if (response == null || (response is List && response.isEmpty)) {
        debugPrint("ProfileService: Supabase update returned no data (possibly no row matched)");
      }
    } catch (e) {
      debugPrint("ProfileService: updateProfile Supabase error: $e");
      // We don't necessarily throw here if Laravel succeeded, 
      // but the user wants "REAL" updates, so let's be strict.
      throw Exception("Échec de la synchronisation de secours (Supabase).");
    }
  }

  Future<String?> uploadProfilePhoto(File imageFile) async {
    final user = _client.auth.currentUser;
    if (user == null) return null;

    try {
      debugPrint("ProfileService: Uploading photo via Laravel API...");
      
      final response = await LaravelService().updateProfile({}, photoFile: imageFile);
      
      if (response.statusCode == 200) {
        final decoded = jsonDecode(response.body);
        if (decoded['status'] == 'success' && decoded['user'] != null) {
          final publicUrl = decoded['user']['photo_url']?.toString();
          if (publicUrl != null) {
            debugPrint("ProfileService: Photo uploaded successfully. URL: $publicUrl");
            
            // Update Supabase backup directly and invalidate cache
            try {
              await _client.from('users').update({'photo_url': publicUrl}).eq('email', user.email!);
            } catch (_) {}
            
            _cachedProfile = null;
            return publicUrl;
          }
        }
        throw Exception("Serveur: JSON invalide ou URL photo manquante.");
      } else {
        throw Exception("Erreur Serveur (Code ${response.statusCode})");
      }
    } catch (e) {
      debugPrint("ProfileService: photo upload error: $e");
      throw Exception("Impossible d'uploader la photo : $e");
    }
  }

  Future<void> changePassword(String currentPassword, String newPassword) async {
    final user = _client.auth.currentUser;
    if (user == null) throw Exception("Utilisateur non connecté");

    try {
      debugPrint("ProfileService: Changing password via Laravel API...");
      final response = await LaravelService().post('$_baseUrl/api/user/change-password', {
        'current_password': currentPassword,
        'password': newPassword,
        'password_confirmation': newPassword,
      });

      if (response.statusCode == 200) {
        final decoded = jsonDecode(response.body);
        if (decoded['status'] == 'success') {
          debugPrint("ProfileService: Password updated successfully via Laravel.");
          return;
        }
        throw Exception(decoded['message'] ?? "Erreur lors du changement de mot de passe.");
      } else {
        final decoded = jsonDecode(response.body);
        throw Exception(decoded['message'] ?? "Erreur Serveur (Code ${response.statusCode})");
      }
    } catch (e) {
      debugPrint("ProfileService: changePassword error: $e");
      rethrow;
    }
  }
}
