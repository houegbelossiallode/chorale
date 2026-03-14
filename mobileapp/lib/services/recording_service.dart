import 'dart:io';
import 'package:supabase_flutter/supabase_flutter.dart';
import 'laravel_service.dart';
import 'package:http/http.dart' as http;

class RecordingService {
  final SupabaseClient _client = Supabase.instance.client;

  Future<void> saveRecording({
    required String localPath,
    required String chantId,
    required String repertoireId,
  }) async {
    final user = _client.auth.currentUser;
    if (user == null) throw Exception("Utilisateur non connecté");

    final file = File(localPath);
    
    // Upload via Laravel Backend to bypass RLS issues
    final streamedResponse = await LaravelService().uploadRecording(
      file: file,
      chantId: chantId,
      repertoireId: repertoireId,
    );

    final response = await http.Response.fromStream(streamedResponse);

    if (response.statusCode != 200) {
      throw Exception("Échec de la sauvegarde sur le serveur (Status: ${response.statusCode}, Body: ${response.body})");
    }
  }

  Future<void> deleteRecording(String id, String filePath) async {
    // 1. Delete from Storage
    try {
      await _client.storage.from('imgs').remove([filePath]);
    } catch (e) {
      // Ignorer si déjà supprimé du storage
    }

    // 2. Delete from Database
    await _client.from('enregistrements').delete().eq('id', id);
  }
}
