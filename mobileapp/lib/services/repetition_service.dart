// lib/services/repetition_service.dart
import 'package:supabase_flutter/supabase_flutter.dart';
import '../models/repetition.dart';
import 'package:flutter/foundation.dart';
import 'profile_service.dart';
import 'laravel_service.dart';

class RepetitionService {
  final SupabaseClient _client = Supabase.instance.client;
  final LaravelService _laravelService = LaravelService();

  Future<List<Repetition>> fetchRepetitions() async {
    final List<dynamic> data = await _client
        .from('repetitions')
        .select('*, sondages(choix)')
        .order('start_time', ascending: false);
    
    return data.map((e) {
      final List<dynamic> sondages = e['sondages'] ?? [];
      final userChoice = sondages.isNotEmpty ? sondages.first['choix'] : null;
      
      final Map<String, dynamic> repData = Map<String, dynamic>.from(e);
      repData['user_choice'] = userChoice;
      
      return Repetition.fromJson(repData);
    }).toList();
  }

  Future<void> updateSondage(String repetitionId, String choice) async {
    final response = await _laravelService.post(
      '${_laravelService.baseUrl}/api/sondages',
      {
        'repetition_id': repetitionId,
        'choix': choice,
      },
    );

    if (response.statusCode != 200) {
      throw Exception("Erreur lors de la mise à jour du sondage: ${response.body}");
    }
  }

  Future<Repetition> fetchRepetitionById(String id) async {
    final Map<String, dynamic> data = await _client
        .from('repetitions')
        .select()
        .eq('id', id)
        .single();
    
    return Repetition.fromJson(data);
  }

  Future<List<Map<String, dynamic>>> fetchRepertoire(String repetitionId) async {
    // La table pivot est repertoire_repetition (repetition_id, repertoire_id)
    final List<dynamic> pivotData = await _client
        .from('repertoire_repetition')
        .select('repertoire_id')
        .eq('repetition_id', repetitionId);

    final List<int> repertoireIds = pivotData.map((e) => e['repertoire_id'] as int).toList();

    if (repertoireIds.isEmpty) return [];

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
        .inFilter('id', repertoireIds);

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
        debugPrint("RepetitionService: fetchRepertoire recordings error: $e");
      }
    }

    return data.cast<Map<String, dynamic>>();
  }

  /// Fetches all repertoire items for a repetition, grouped by their associated event title.
  Future<Map<String, List<Map<String, dynamic>>>> fetchRepertoireGroupedByEvent(String repetitionId) async {
    // Get pivot rows for this repetition
    final List<dynamic> pivotData = await _client
        .from('repertoire_repetition')
        .select('repertoire_id')
        .eq('repetition_id', repetitionId);

    final List<int> repertoireIds = pivotData.map((e) => e['repertoire_id'] as int).toList();
    if (repertoireIds.isEmpty) return {};

    // Fetch repertoire items with event info and chant info
    final List<dynamic> data = await _client
        .from('repertoire')
        .select('''
          id,
          event_id,
          partie_event_id,
          partie_events (id, titre, ordre),
          events (id, title),
          chants (
            id,
            title,
            composer,
            parole,
            file_path,
            fichier_chants (id, type, file_path, pupitre_id, pupitres(name))
          )
        ''')
        .inFilter('id', repertoireIds);

    // Sort by partie_events.ordre
    data.sort((a, b) {
      final orderA = a['partie_events']?['ordre'] ?? 999;
      final orderB = b['partie_events']?['ordre'] ?? 999;
      return orderA.compareTo(orderB);
    });

    // Fetch user recordings using the Laravel integer ID
    final profileService = ProfileService();
    final internalUserId = await profileService.getIntegerUserId();

    if (internalUserId != null && data.isNotEmpty) {
      try {
        final List<dynamic> recordings = await _client
            .from('enregistrements')
            .select('id, file_path, chant_id, repertoire_id')
            .eq('user_id', internalUserId)
            .inFilter('repertoire_id', repertoireIds);

        for (var i = 0; i < data.length; i++) {
          final repId = data[i]['id'] as int;
          final chantRecordings = recordings.where((r) => r['repertoire_id'] == repId).toList();
          if (data[i]['chants'] != null) {
            data[i]['chants']['enregistrements'] = chantRecordings;
          }
        }
      } catch (e) {
        debugPrint("RepetitionService: recordings fetch error: $e");
      }
    }

    // Group by event title
    final Map<String, List<Map<String, dynamic>>> grouped = {};
    for (var item in data) {
      final eventTitle = (item['events'] as Map<String, dynamic>?)?['title'] as String? ?? 'Événement';
      grouped.putIfAbsent(eventTitle, () => []).add(item as Map<String, dynamic>);
    }

    return grouped;
  }
}
