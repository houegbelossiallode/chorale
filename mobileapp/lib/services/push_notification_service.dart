import 'package:firebase_messaging/firebase_messaging.dart';
import 'package:flutter/foundation.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'laravel_service.dart';

class PushNotificationService {
  final FirebaseMessaging _fcm = FirebaseMessaging.instance;
  final LaravelService _laravelService = LaravelService();

  Future<void> initialize() async {
    // Request permissions
    NotificationSettings settings = await _fcm.requestPermission(
      alert: true,
      badge: true,
      sound: true,
    );

    if (settings.authorizationStatus == AuthorizationStatus.authorized) {
      debugPrint('User granted permission');
      
      // Get token
      String? token = await _fcm.getToken();
      if (token != null) {
        debugPrint("FCM Token: $token");
        // We wait for Laravel session sync to update the token on server
        // await _updateTokenOnServer(token);
      }
    } else {
      debugPrint('User declined or has not accepted permission');
    }

    // Handle token refresh
    _fcm.onTokenRefresh.listen((newToken) async {
      await _updateTokenOnServer(newToken);
    });

    // Handle foreground messages
    FirebaseMessaging.onMessage.listen((RemoteMessage message) {
      debugPrint('Got a message whilst in the foreground!');
      debugPrint('Message data: ${message.data}');

      if (message.notification != null) {
        debugPrint('Message also contained a notification: ${message.notification}');
      }
    });
  }

  Future<void> syncToken() async {
    String? token = await _fcm.getToken();
    if (token != null) {
      debugPrint("PushNotificationService: Manual sync for token: $token");
      await _updateTokenOnServer(token);
    }
  }

  Future<void> _updateTokenOnServer(String token) async {
    try {
      final response = await _laravelService.post(
        '${_laravelService.baseUrl}/api/user/fcm-token',
        {'fcm_token': token},
      );
      if (response.statusCode == 200) {
        debugPrint("PushNotificationService: FCM token successfully synced with backend");
      } else {
        debugPrint("PushNotificationService: Failed to sync FCM token: ${response.statusCode} - ${response.body}");
      }
    } catch (e) {
      debugPrint("PushNotificationService: Error syncing FCM token: $e");
    }
  }
}
