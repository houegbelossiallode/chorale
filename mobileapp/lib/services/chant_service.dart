// lib/services/chant_service.dart
import 'package:supabase_flutter/supabase_flutter.dart';
import '../models/chant.dart';

class ChantService {
  final SupabaseClient _client = Supabase.instance.client;

  Future<List<Chant>> fetchChants() async {
    final List<dynamic> data = await _client.from('chants').select().order('title');
    return data.map((e) => Chant.fromJson(e)).toList();
  }

  Future<Chant> fetchChantById(String id) async {
    final Map<String, dynamic> data = await _client.from('chants').select().eq('id', id).single();
    return Chant.fromJson(data);
  }
}
