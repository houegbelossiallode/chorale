import 'package:flutter/material.dart';
import 'package:supabase_flutter/supabase_flutter.dart';
import 'package:choralia/features/auth/login_screen.dart';
import 'package:choralia/features/dashboard/dashboard_screen.dart';
import '../../services/push_notification_service.dart';
import '../../services/laravel_service.dart';

class AuthWrapper extends StatefulWidget {
  const AuthWrapper({super.key});

  @override
  State<AuthWrapper> createState() => _AuthWrapperState();
}

class _AuthWrapperState extends State<AuthWrapper> {
  @override
  void initState() {
    super.initState();
    // Proactively sync session if already logged in
    final session = Supabase.instance.client.auth.currentSession;
    if (session != null) {
      LaravelService().syncSession().then((success) {
        if (success) {
          PushNotificationService().syncToken();
        }
      });
    }
    
    // Listen for auth state changes
    Supabase.instance.client.auth.onAuthStateChange.listen((data) {
      final AuthChangeEvent event = data.event;
      final Session? session = data.session;
      
      if (event == AuthChangeEvent.signedIn && session != null) {
        LaravelService().syncSession().then((success) {
          if (success) {
            PushNotificationService().syncToken();
          }
        });
      }
    });
  }

  @override
  Widget build(BuildContext context) {
    return StreamBuilder<AuthState>(
      stream: Supabase.instance.client.auth.onAuthStateChange,
      builder: (context, snapshot) {
        if (snapshot.connectionState == ConnectionState.waiting) {
          return const Scaffold(body: Center(child: CircularProgressIndicator()));
        }

        final session = snapshot.data?.session;
        if (session != null) {
          return const DashboardScreen();
        } else {
          return const LoginScreen();
        }
      },
    );
  }
}
