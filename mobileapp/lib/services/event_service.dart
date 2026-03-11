// lib/services/event_service.dart
import 'package:supabase_flutter/supabase_flutter.dart';
import 'package:flutter/foundation.dart';
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

  Future<List<Map<String, dynamic>>> fetchRepertoire(String eventId) async {
    final List<dynamic> data = await _client
        .from('repertoire')
        .select('''
          id,
          partie_event_id,
          partie_events (id, titre, ordre),
          chants (
            id,
            title,
            composer,
            parole,
            fichier_chants (id, type, file_path, pupitre_id, pupitres(name))
          )
        ''')
        .eq('event_id', eventId);

    // Fetch user recordings separately to avoid Supabase deep embedded resource errors
    final userId = _client.auth.currentUser?.id;
    if (userId != null && data.isNotEmpty) {
      final List<int> repertoireIds = data.map((e) => e['id'] as int).toList();
      
      try {
        final List<dynamic> recordings = await _client
          .from('enregistrements')
          .select('id, file_path, chant_id, repertoire_id')
          .eq('user_id', userId)
          .inFilter('repertoire_id', repertoireIds);

        // Map recordings back to their respective chants in the repertoire
        for (var i = 0; i < data.length; i++) {
          final repId = data[i]['id'] as int;
          final chantRecordings = recordings.where((r) => r['repertoire_id'] == repId).toList();
          
          if (data[i]['chants'] != null) {
            data[i]['chants']['enregistrements'] = chantRecordings;
          }
        }
      } catch (e) {
        debugPrint("Erreur lors de la récupération des enregistrements: $e");
      }
    }

    return data.cast<Map<String, dynamic>>();
  }
}
