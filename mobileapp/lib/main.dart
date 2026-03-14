import 'package:flutter/material.dart';
import 'package:supabase_flutter/supabase_flutter.dart';
import 'package:flutter_dotenv/flutter_dotenv.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:choralia/features/auth/auth_wrapper.dart';
import 'package:intl/date_symbol_data_local.dart';

void main() async {
  WidgetsFlutterBinding.ensureInitialized();
  
  await initializeDateFormatting('fr_FR', null);
  await dotenv.load(fileName: ".env");

  await Supabase.initialize(
    url: dotenv.env['SUPABASE_URL']!,
    anonKey: dotenv.env['SUPABASE_ANON_KEY']!,
  );

  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Chorale St Oscar Romero',
      debugShowCheckedModeBanner: false,
      theme: ThemeData(
        colorScheme: ColorScheme.fromSeed(
          seedColor: const Color(0xFF7367F0),
          primary: const Color(0xFF7367F0),
          secondary: const Color(0xFFC9A84C), // Or Premium
          tertiary: const Color(0xFFEA5455),  // Vin/Rouge
          surface: Colors.white,
        ),
        useMaterial3: true,
        textTheme: GoogleFonts.outfitTextTheme(
          Theme.of(context).textTheme.apply(
            bodyColor: const Color(0xFF444050),
            displayColor: const Color(0xFF444050),
          ),
        ),
        scaffoldBackgroundColor: const Color(0xFFFAFAFE),
        cardTheme: CardThemeData(
          elevation: 2,
          shadowColor: Colors.black.withAlpha(25),
          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
          color: Colors.white,
        ),
        appBarTheme: AppBarTheme(
          backgroundColor: Colors.white,
          foregroundColor: const Color(0xFF444050),
          elevation: 0,
          centerTitle: true,
          titleTextStyle: GoogleFonts.outfit(
            color: const Color(0xFF444050),
            fontSize: 20,
            fontWeight: FontWeight.bold,
          ),
          iconTheme: const IconThemeData(color: Color(0xFF444050)),
        ),
        elevatedButtonTheme: ElevatedButtonThemeData(
          style: ElevatedButton.styleFrom(
            elevation: 2,
            shadowColor: const Color(0xFF7367F0).withAlpha(77),
            padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 12),
            shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
          ),
        ),
      ),
      home: const AuthWrapper(),
    );
  }
}
