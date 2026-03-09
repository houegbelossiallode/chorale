// lib/services/profile_service.dart
import 'package:supabase_flutter/supabase_flutter.dart';

class ProfileService {
  final SupabaseClient _client = Supabase.instance.client;

  Future<Map<String, dynamic>?> fetchProfile() async {
    final user = _client.auth.currentUser;
    if (user == null) return null;

    final Map<String, dynamic> data = await _client
        .from('profiles')
        .select()
        .eq('id', user.id)
        .single();
    
    return data;
  }

  Future<void> updateProfile(Map<String, dynamic> updates) async {
    final user = _client.auth.currentUser;
    if (user == null) return;

    await _client
        .from('profiles')
        .update(updates)
        .eq('id', user.id);
  }
}
