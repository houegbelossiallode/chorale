import 'dart:io';
import 'package:http/http.dart' as http;
import 'package:supabase_flutter/supabase_flutter.dart';
import 'package:flutter_dotenv/flutter_dotenv.dart';
import 'package:flutter/foundation.dart';

class LaravelService {
  static final LaravelService _instance = LaravelService._internal();
  factory LaravelService() => _instance;
  LaravelService._internal();

  final String _baseUrl = dotenv.env['BACKEND_URL'] ?? 'https://chorale.onrender.com';
  String? _cookies;

  Future<bool> syncSession() async {
    final session = Supabase.instance.client.auth.currentSession;
    if (session == null) {
      debugPrint("LaravelService: No active Supabase session");
      return false;
    }

    try {
      debugPrint("LaravelService: Syncing session with Laravel at $_baseUrl/api/supabase-login");
      final response = await http.post(
        Uri.parse('$_baseUrl/api/supabase-login'),
        body: {'access_token': session.accessToken},
      );

      if (response.statusCode == 200) {
        _cookies = response.headers['set-cookie'];
        debugPrint("LaravelService: Session synced successfully");
        return true;
      } else {
        debugPrint("LaravelService: Sync failed with status ${response.statusCode}");
        debugPrint("Response: ${response.body}");
      }
    } catch (e) {
      debugPrint("LaravelService: Sync error: $e");
    }
    return false;
  }

  Future<http.Response> get(String url) async {
    if (_cookies == null) {
      await syncSession();
    }

    return http.get(
      Uri.parse(url),
      headers: {
        'cookie': _cookies ?? '',
        'Accept': 'application/json',
      },
    );
  }

  Future<http.StreamedResponse> uploadRecording({
    required File file,
    required String chantId,
    String? repertoireId,
  }) async {
    if (_cookies == null) {
      await syncSession();
    }

    var request = http.MultipartRequest('POST', Uri.parse('$_baseUrl/choriste/enregistrements'));
    request.headers.addAll({
      'cookie': _cookies ?? '',
      'Accept': 'application/json',
      'X-Requested-With': 'XMLHttpRequest',
    });

    request.fields['chant_id'] = chantId;
    if (repertoireId != null) {
      request.fields['repertoire_id'] = repertoireId;
    }

    request.files.add(await http.MultipartFile.fromPath('audio', file.path));

    return request.send();
  }

  Future<http.Response> updateProfile(Map<String, dynamic> data) async {
    if (_cookies == null) {
      await syncSession();
    }

    return http.post(
      Uri.parse('$_baseUrl/api/sync-profile'),
      headers: {
        'cookie': _cookies ?? '',
        'Accept': 'application/json',
      },
      body: data.map((key, value) => MapEntry(key, value?.toString() ?? '')),
    );
  }
}
