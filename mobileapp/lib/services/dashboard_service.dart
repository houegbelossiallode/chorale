import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:flutter_dotenv/flutter_dotenv.dart';
import 'laravel_service.dart';

class DashboardService {
  final String _baseUrl = dotenv.env['BACKEND_URL'] ?? "https://chorale.onrender.com";

  Future<Map<String, dynamic>?> fetchDashboardStats() async {
    try {
      final response = await LaravelService().get('$_baseUrl/api/dashboard/stats');
      
      if (response.statusCode == 200) {
        final Map<String, dynamic> data = jsonDecode(response.body);
        if (data['status'] == 'success') {
          return data['data'];
        }
      }
      return null;
    } catch (e) {
      return null;
    }
  }
}
