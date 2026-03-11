import 'package:supabase_flutter/supabase_flutter.dart';
import 'dart:io';
import 'laravel_service.dart';

class ProfileService {
  final SupabaseClient _client = Supabase.instance.client;

  Future<Map<String, dynamic>?> fetchProfile() async {
    final user = _client.auth.currentUser;
    if (user == null || user.email == null) return null;

    try {
      final Map<String, dynamic> data = await _client
          .from('users')
          .select('id, first_name, last_name, email, activite, hobbie, citation, love_choir, date_naissance, photo_url, pupitre_id, pupitres(name)')
          .eq('email', user.email!)
          .single();
      
      return data;
    } catch (e) {
      return null;
    }
  }

  Future<void> updateProfile(Map<String, dynamic> updates) async {
    final user = _client.auth.currentUser;
    if (user == null || user.email == null) return;

    await _client
        .from('users')
        .update(updates)
        .eq('email', user.email!);

    // Sync with Laravel
    try {
      await LaravelService().updateProfile(updates);
    } catch (e) {
      // Ignorer l'erreur de sync si Supabase a réussi
    }
  }

  Future<String?> uploadProfilePhoto(File imageFile) async {
    final user = _client.auth.currentUser;
    if (user == null) return null;

    try {
      final fileName = "${user.id}_${DateTime.now().millisecondsSinceEpoch}.jpg";
      final path = "profiles/$fileName";
      
      await _client.storage.from('imgs').upload(path, imageFile);
      
      final String publicUrl = _client.storage.from('imgs').getPublicUrl(path);
      return publicUrl;
    } catch (e) {
      return null;
    }
  }
}
