import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:supabase_flutter/supabase_flutter.dart';
import 'package:chorale_app_mobile/features/chants/chants_screen.dart';
import 'package:chorale_app_mobile/features/agenda/agenda_screen.dart';

class DashboardScreen extends StatefulWidget {
  const DashboardScreen({super.key});

  @override
  State<DashboardScreen> createState() => _DashboardScreenState();
}

class _DashboardScreenState extends State<DashboardScreen> {
  int _selectedIndex = 0;

  final List<Widget> _screens = [
    const HomeScreen(),
    const ChantsScreen(),
    const AgendaScreen(),
    const ProfileScreen(),
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: _screens[_selectedIndex],
      bottomNavigationBar: Container(
        decoration: BoxDecoration(
          color: Colors.white,
          boxShadow: [
            BoxShadow(
              color: Colors.black.withOpacity(0.05),
              blurRadius: 20,
              offset: const Offset(0, -5),
            ),
          ],
        ),
        child: SafeArea(
          child: Padding(
            padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 8),
            child: BottomNavigationBar(
              currentIndex: _selectedIndex,
              onTap: (index) => setState(() => _selectedIndex = index),
              type: BottomNavigationBarType.fixed,
              backgroundColor: Colors.transparent,
              elevation: 0,
              selectedItemColor: const Color(0xFF7367F0),
              unselectedItemColor: Colors.slate.shade300,
              selectedLabelStyle: const TextStyle(fontWeight: FontWeight.bold, fontSize: 12),
              unselectedLabelStyle: const TextStyle(fontWeight: FontWeight.w500, fontSize: 12),
              items: const [
                BottomNavigationBarItem(icon: Icon(Icons.home_rounded), label: 'Accueil'),
                BottomNavigationBarItem(icon: Icon(Icons.library_music_rounded), label: 'Chants'),
                BottomNavigationBarItem(icon: Icon(Icons.calendar_today_rounded), label: 'Agenda'),
                BottomNavigationBarItem(icon: Icon(Icons.person_rounded), label: 'Profil'),
              ],
            ),
          ),
        ),
      ),
    );
  }
}

class HomeScreen extends StatelessWidget {
  const HomeScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final user = Supabase.instance.client.auth.currentUser;
    final name = user?.userMetadata?['first_name'] ?? 'Choriste';

    return CustomScrollView(
      slivers: [
        SliverAppBar(
          expandedHeight: 120,
          floating: false,
          pinned: true,
          backgroundColor: const Color(0xFF7367F0),
          flexibleSpace: FlexibleSpaceBar(
            titlePadding: const EdgeInsets.symmetric(horizontal: 20, vertical: 16),
            title: Text(
              "Salut, $name",
              style: GoogleFonts.outfit(fontWeight: FontWeight.bold, fontSize: 18, color: Colors.white),
            ),
          ),
          actions: [
            IconButton(
              icon: const Icon(Icons.notifications_none_rounded, color: Colors.white),
              onPressed: () {},
            ),
            const SizedBox(width: 10),
          ],
        ),
        SliverToBoxAdapter(
          child: Padding(
            padding: const EdgeInsets.all(20),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                _buildSectionHeader("Activités Récentes"),
                const SizedBox(height: 15),
                _buildActivityCard(
                  "Répétition à venir",
                  "Samedi 16:00",
                  Icons.event_repeat_rounded,
                  const Color(0xFF7367F0),
                ),
                const SizedBox(height: 12),
                _buildActivityCard(
                  "Nouveau Chant",
                  "Gloria in excelsis Deo",
                  Icons.music_note_rounded,
                  const Color(0xFF28C76F),
                ),
                const SizedBox(height: 30),
                _buildSectionHeader("Ton Pupitre"),
                const SizedBox(height: 15),
                Container(
                  padding: const EdgeInsets.all(20),
                  decoration: BoxDecoration(
                    gradient: const LinearGradient(
                      colors: [Color(0xFFC9A84C), Color(0xFFE8D48B)],
                    ),
                    borderRadius: BorderRadius.circular(24),
                  ),
                  child: Row(
                    children: [
                      Container(
                        padding: const EdgeInsets.all(12),
                        decoration: BoxDecoration(
                          color: Colors.white.withOpacity(0.2),
                          borderRadius: BorderRadius.circular(15),
                        ),
                        child: const Icon(Icons.mic_rounded, color: Colors.white, size: 30),
                      ),
                      const SizedBox(width: 15),
                      const Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text("Ténor", style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 20)),
                          Text("45 membres", style: TextStyle(color: Colors.white70, fontSize: 14)),
                        ],
                      ),
                    ],
                  ),
                ),
              ],
            ),
          ),
        ),
      ],
    );
  }

  Widget _buildSectionHeader(String title) {
    return Text(
      title,
      style: GoogleFonts.outfit(fontSize: 18, fontWeight: FontWeight.bold, color: const Color(0xFF444050)),
    );
  }

  Widget _buildActivityCard(String title, String subtitle, IconData icon, Color color) {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(20),
        border: Border.all(color: Colors.slate.shade100),
      ),
      child: Row(
        children: [
          Container(
            padding: const EdgeInsets.all(10),
            decoration: BoxDecoration(
              color: color.withOpacity(0.1),
              borderRadius: BorderRadius.circular(12),
            ),
            child: Icon(icon, color: color, size: 22),
          ),
          const SizedBox(width: 15),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(title, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 15, color: Color(0xFF444050))),
                Text(subtitle, style: TextStyle(fontSize: 13, color: Colors.slate.shade400)),
              ],
            ),
          ),
          Icon(Icons.chevron_right_rounded, color: Colors.slate.shade300),
        ],
      ),
    );
  }
}

class ProfileScreen extends StatelessWidget {
  const ProfileScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text("Profil")),
      body: Center(
        child: ElevatedButton(
          onPressed: () => Supabase.instance.client.auth.signOut(),
          child: const Text("Déconnexion"),
        ),
      ),
    );
  }
}
