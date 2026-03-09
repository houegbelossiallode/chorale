// lib/core/router.dart
import 'package:flutter/material.dart';
import 'package:chorale_app_mobile/features/chants/chants_screen.dart';
import 'package:chorale_app_mobile/features/events/events_list_screen.dart';
import 'package:chorale_app_mobile/features/repetitions/repetitions_list_screen.dart';
import 'package:chorale_app_mobile/features/profile/profile_screen.dart';
import 'package:chorale_app_mobile/features/recorder/recorder_screen.dart';
import 'package:chorale_app_mobile/features/dashboard/dashboard_screen.dart';

class AppRouter {
  static Route<dynamic> generateRoute(RouteSettings settings) {
    switch (settings.name) {
      case '/':
        return MaterialPageRoute(builder: (_) => const DashboardScreen());
      case '/chants':
        return MaterialPageRoute(builder: (_) => const ChantsScreen());
      case '/events':
        return MaterialPageRoute(builder: (_) => const EventsListScreen());
      case '/repetitions':
        return MaterialPageRoute(builder: (_) => const RepetitionsListScreen());
      case '/profile':
        return MaterialPageRoute(builder: (_) => const ProfileScreen());
      case '/recorder':
        return MaterialPageRoute(builder: (_) => const RecorderScreen());
      default:
        return MaterialPageRoute(
          builder: (_) => Scaffold(
            body: Center(child: Text('Route ${settings.name} not found')),
          ),
        );
    }
  }
}
