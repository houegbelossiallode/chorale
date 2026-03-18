import 'package:flutter/material.dart';
import 'package:supabase_flutter/supabase_flutter.dart';
import 'package:choralia/features/auth/login_screen.dart';
import 'package:choralia/features/dashboard/dashboard_screen.dart';
import 'package:choralia/features/auth/force_password_change_screen.dart';
import '../../services/push_notification_service.dart';
import '../../services/laravel_service.dart';

class AuthWrapper extends StatefulWidget {
  const AuthWrapper({super.key});

  @override
  State<AuthWrapper> createState() => _AuthWrapperState();
}

class _AuthWrapperState extends State<AuthWrapper> {
  bool _mustChangePassword = false;
  bool _isSyncing = false;

  @override
  void initState() {
    super.initState();
    // Request notification permissions after the first frame
    WidgetsBinding.instance.addPostFrameCallback((_) {
      PushNotificationService().requestPermissions();
    });

    // Listen for auth state changes
    Supabase.instance.client.auth.onAuthStateChange.listen((data) {
      final AuthChangeEvent event = data.event;
      final Session? session = data.session;

      if (event == AuthChangeEvent.signedIn && session != null) {
        _performSync();
      } else if (event == AuthChangeEvent.signedOut) {
        setState(() {
          _mustChangePassword = false;
        });
      }
    });

    // Initial check
    if (Supabase.instance.client.auth.currentSession != null) {
      _performSync();
    }
  }

  Future<void> _performSync() async {
    setState(() => _isSyncing = true);
    final success = await LaravelService().syncSession();
    if (success) {
      if (mounted) {
        setState(() {
          _mustChangePassword = LaravelService().mustChangePassword;
          _isSyncing = false;
        });
        PushNotificationService().syncToken();
      }
    } else {
      if (mounted) setState(() => _isSyncing = false);
    }
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
          if (_isSyncing) {
            return const Scaffold(body: Center(child: CircularProgressIndicator()));
          }
          if (_mustChangePassword) {
            return const ForcePasswordChangeScreen();
          }
          return const DashboardScreen();
        } else {
          return const LoginScreen();
        }
      },
    );
  }
}
