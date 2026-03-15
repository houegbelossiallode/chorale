import 'package:firebase_messaging/firebase_messaging.dart';
import 'package:supabase_flutter/supabase_flutter.dart';
import 'package:flutter/foundation.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'laravel_service.dart';

import 'package:flutter_local_notifications/flutter_local_notifications.dart';

class PushNotificationService {
  final FirebaseMessaging _fcm = FirebaseMessaging.instance;
  final LaravelService _laravelService = LaravelService();
  final FlutterLocalNotificationsPlugin _localNotifications = FlutterLocalNotificationsPlugin();

  // Create a high importance channel for Android
  static const AndroidNotificationChannel _channel = AndroidNotificationChannel(
    'high_importance_channel', // id
    'High Importance Notifications', // title
    description: 'This channel is used for important notifications.', // description
    importance: Importance.max,
  );

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
      }
    }

    // Initialize local notifications
    const AndroidInitializationSettings initializationSettingsAndroid =
        AndroidInitializationSettings('@mipmap/launcher_icon');
    
    const InitializationSettings initializationSettings = InitializationSettings(
      android: initializationSettingsAndroid,
      iOS: DarwinInitializationSettings(),
    );

    await _localNotifications.initialize(initializationSettings);

    // Create the channel on Android
    await _localNotifications
        .resolvePlatformSpecificImplementation<AndroidFlutterLocalNotificationsPlugin>()
        ?.createNotificationChannel(_channel);

    // Handle token refresh
    _fcm.onTokenRefresh.listen((newToken) async {
      await _updateTokenOnServer(newToken);
    });

    // Handle foreground messages
    FirebaseMessaging.onMessage.listen((RemoteMessage message) {
      debugPrint('Got a message whilst in the foreground!');
      
      RemoteNotification? notification = message.notification;
      AndroidNotification? android = message.notification?.android;

      if (notification != null && android != null && !kIsWeb) {
        _localNotifications.show(
          notification.hashCode,
          notification.title,
          notification.body,
          NotificationDetails(
            android: AndroidNotificationDetails(
              _channel.id,
              _channel.name,
              channelDescription: _channel.description,
              icon: android.smallIcon,
              importance: Importance.max,
              priority: Priority.high,
            ),
          ),
          payload: jsonEncode(message.data),
        );
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
      final user = Supabase.instance.client.auth.currentUser;
      final response = await _laravelService.post(
        '${_laravelService.baseUrl}/api/user/fcm-token',
        {
          'fcm_token': token,
          if (user?.email != null) 'email': user!.email,
          if (user?.id != null) 'supabase_id': user!.id,
        },
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
