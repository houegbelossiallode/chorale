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
}
