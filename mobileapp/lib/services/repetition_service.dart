// lib/services/repetition_service.dart
import 'package:supabase_flutter/supabase_flutter.dart';
import '../models/repetition.dart';

class RepetitionService {
  final SupabaseClient _client = Supabase.instance.client;

  Future<List<Repetition>> fetchRepetitions() async {
    final List<dynamic> data = await _client
        .from('repetitions')
        .select()
        .order('start_time', ascending: false);
    
    return data.map((e) => Repetition.fromJson(e)).toList();
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
            fichier_chants (id, type, file_path, pupitre_id, pupitres(name))
          )
        ''')
        .inFilter('id', repertoireIds);

    // Fetch user recordings separately to avoid Supabase deep embedded resource errors
    final userId = _client.auth.currentUser?.id;
    if (userId != null && data.isNotEmpty) {
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
        // Ignorer
      }
    }

    return data.cast<Map<String, dynamic>>();
  }
}
