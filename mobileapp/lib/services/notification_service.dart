import 'package:supabase_flutter/supabase_flutter.dart';

class NotificationService {
  final SupabaseClient _client = Supabase.instance.client;

  Future<List<Map<String, dynamic>>> fetchNotifications() async {
    final user = _client.auth.currentUser;
    if (user == null || user.email == null) return [];

    try {
      // 1. Get the bigint user ID
      final userData = await _client
          .from('users')
          .select('id')
          .eq('email', user.email!)
          .single();
      
      final int userId = userData['id'];

      // 2. Fetch notifications
      final List<dynamic> data = await _client
          .from('notifications')
          .select('*')
          .eq('notifiable_id', userId)
          .eq('notifiable_type', 'App\\Models\\User')
          .order('created_at', ascending: false);
      
      return List<Map<String, dynamic>>.from(data);
    } catch (e) {
      return [];
    }
  }

  Future<void> markAsRead(String id) async {
    await _client
        .from('notifications')
        .update({'read_at': DateTime.now().toIso8601String()})
        .eq('id', id);
  }

  Future<void> markAllAsRead() async {
    final user = _client.auth.currentUser;
    if (user == null || user.email == null) return;

    final userData = await _client
        .from('users')
        .select('id')
        .eq('email', user.email!)
        .single();
    
    final int userId = userData['id'];

    await _client
        .from('notifications')
        .update({'read_at': DateTime.now().toIso8601String()})
        .eq('notifiable_id', userId)
        .eq('notifiable_type', 'App\\Models\\User')
        .isFilter('read_at', null);
  }
}
