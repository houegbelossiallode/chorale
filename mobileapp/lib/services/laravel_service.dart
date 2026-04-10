import 'dart:io';
import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:supabase_flutter/supabase_flutter.dart';
import 'package:flutter_dotenv/flutter_dotenv.dart';
import 'package:flutter/foundation.dart';

class LaravelService {
  static final LaravelService _instance = LaravelService._internal();
  factory LaravelService() => _instance;
  LaravelService._internal();

  final String _baseUrl = dotenv.env['BACKEND_URL'] ?? 'https://romero-38dc.onrender.com';
  String? _cookies;
  String? _csrfToken;
  bool _mustChangePassword = false;
  Map<String, dynamic>? _cachedUser;

  String get baseUrl => _baseUrl;
  bool get mustChangePassword => _mustChangePassword;
  Map<String, dynamic>? get cachedUser => _cachedUser;

  /// Extract and store cookies from a set-cookie header string
  void _parseCookies(String? rawCookie) {
    if (rawCookie == null) return;
    final cookies = <String>[];
    final parts = rawCookie.split(',');
    for (var part in parts) {
      final subParts = part.split(';');
      final cookiePart = subParts[0].trim();
      final name = cookiePart.toLowerCase();
      if (cookiePart.contains('=') &&
          !name.startsWith('expires=') &&
          !name.startsWith('path=') &&
          !name.startsWith('max-age=') &&
          !name.startsWith('domain=') &&
          !name.startsWith('samesite=')) {
        cookies.add(cookiePart);
        // Extract XSRF-TOKEN for CSRF protection
        if (name.startsWith('xsrf-token=')) {
          _csrfToken = Uri.decodeComponent(cookiePart.substring('XSRF-TOKEN='.length));
        }
      }
    }
    _cookies = cookies.join('; ');
  }

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
        _parseCookies(response.headers['set-cookie']);
        
        final decoded = jsonDecode(response.body);
        if (decoded['user'] != null) {
          _cachedUser = Map<String, dynamic>.from(decoded['user']);
          _mustChangePassword = decoded['user']['must_change_password'] == true;
          debugPrint("LaravelService: Session synced, user cached, mustChangePassword: $_mustChangePassword");
        }
        
        debugPrint("LaravelService: Session synced, cookies: $_cookies, csrf: $_csrfToken");
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

  Future<http.Response> post(String url, Map<String, dynamic> data) async {
    if (_cookies == null) {
      await syncSession();
    }

    return http.post(
      Uri.parse(url),
      headers: {
        'cookie': _cookies ?? '',
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        if (_csrfToken != null) 'X-XSRF-TOKEN': _csrfToken!,
      },
      body: jsonEncode(data),
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

    // If still no CSRF token, fetch it from the CSRF cookie endpoint
    if (_csrfToken == null) {
      try {
        final csrfResponse = await http.get(
          Uri.parse('$_baseUrl/sanctum/csrf-cookie'),
          headers: {'cookie': _cookies ?? '', 'Accept': 'application/json'},
        );
        _parseCookies(csrfResponse.headers['set-cookie']);
        debugPrint("LaravelService: CSRF fetched: $_csrfToken");
      } catch (e) {
        debugPrint("LaravelService: CSRF fetch error: $e");
      }
    }

    var request = http.MultipartRequest('POST', Uri.parse('$_baseUrl/choriste/enregistrements'));
    request.headers.addAll({
      'cookie': _cookies ?? '',
      'Accept': 'application/json',
      'X-Requested-With': 'XMLHttpRequest',
      if (_csrfToken != null) 'X-XSRF-TOKEN': _csrfToken!,
    });

    request.fields['chant_id'] = chantId;
    if (repertoireId != null) {
      request.fields['repertoire_id'] = repertoireId;
    }

    request.files.add(await http.MultipartFile.fromPath('audio', file.path));

    return request.send();
  }

  Future<http.Response> updateProfile(Map<String, dynamic> data, {File? photoFile}) async {
    if (_cookies == null) {
      await syncSession();
    }

    if (photoFile != null) {
      final request = http.MultipartRequest(
        'POST',
        Uri.parse('$_baseUrl/api/sync-profile'),
      );

      request.headers.addAll({
        'cookie': _cookies ?? '',
        'Accept': 'application/json',
        if (_csrfToken != null) 'X-XSRF-TOKEN': _csrfToken!,
      });

      data.forEach((key, value) {
        if (value != null) {
          request.fields[key] = value.toString();
        }
      });

      request.files.add(await http.MultipartFile.fromPath('photo', photoFile.path));

      final streamedResponse = await request.send();
      return await http.Response.fromStream(streamedResponse);
    } else {
      return http.post(
        Uri.parse('$_baseUrl/api/sync-profile'),
        headers: {
          'cookie': _cookies ?? '',
          'Accept': 'application/json',
          if (_csrfToken != null) 'X-XSRF-TOKEN': _csrfToken!,
        },
        body: data.map((key, value) => MapEntry(key, value?.toString() ?? '')),
      );
    }
  }
}
