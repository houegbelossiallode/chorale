// lib/services/event_service.dart
import 'package:supabase_flutter/supabase_flutter.dart';
import 'package:flutter/foundation.dart';
import '../models/event.dart';
import 'profile_service.dart';
import 'laravel_service.dart';

class EventService {
  final SupabaseClient _client = Supabase.instance.client;
  final LaravelService _laravelService = LaravelService();

  Future<List<Event>> fetchEvents() async {
    final userId = _client.auth.currentUser?.id;
    
    // We join sondages table to get the user's choice and types table for the label
    final List<dynamic> data = await _client
        .from('events')
        .select('*, sondages(choix), types(libelle)')
        .order('start_at', ascending: true);
        
    return data.map((e) {
      final List<dynamic> sondages = e['sondages'] ?? [];
      // If we have a sondage for this event, we take the choice
      // Note: Supabase select join might return multiple if not filtered, 
      // but our table constraint ensures 1 per user/event.
      final userChoice = sondages.isNotEmpty ? sondages.first['choix'] : null;
      
      final Map<String, dynamic> eventData = Map<String, dynamic>.from(e);
      eventData['user_choice'] = userChoice;
      
      return Event.fromJson(eventData);
    }).toList();
  }

  Future<void> updateSondage(String eventId, String choice) async {
    final response = await _laravelService.post(
      '${_laravelService.baseUrl}/api/sondages',
      {
        'event_id': eventId,
        'choix': choice,
      },
    );

    if (response.statusCode != 200) {
      throw Exception("Erreur lors de la mise à jour du sondage: ${response.body}");
    }
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
            file_path,
            fichier_chants (id, type, file_path, pupitre_id, pupitres(name))
          )
        ''')
        .eq('event_id', eventId);

    // Sort by partie_events.ordre
    data.sort((a, b) {
      final orderA = a['partie_events']?['ordre'] ?? 999;
      final orderB = b['partie_events']?['ordre'] ?? 999;
      return orderA.compareTo(orderB);
    });

    // Fetch user recordings separately using the Laravel integer ID
    final profileService = ProfileService();
    final internalUserId = await profileService.getIntegerUserId();

    if (internalUserId != null && data.isNotEmpty) {
      final List<int> repertoireIds = data.map((e) => e['id'] as int).toList();
      
      try {
        final List<dynamic> recordings = await _client
          .from('enregistrements')
          .select('id, file_path, chant_id, repertoire_id')
          .eq('user_id', internalUserId)
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
