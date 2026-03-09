// lib/services/event_service.dart
import 'package:supabase_flutter/supabase_flutter.dart';
import '../models/event.dart';

class EventService {
  final SupabaseClient _client = Supabase.instance.client;

  Future<List<Event>> fetchEvents() async {
    final List<dynamic> data = await _client
        .from('events')
        .select()
        .order('start_at', ascending: true);
    return data.map((e) => Event.fromJson(e)).toList();
  }

  Future<Event> fetchEventById(String id) async {
    final Map<String, dynamic> data = await _client.from('events').select().eq('id', id).single();
    return Event.fromJson(data);
  }
}
