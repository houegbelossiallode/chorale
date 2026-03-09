import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:supabase_flutter/supabase_flutter.dart';
import 'package:chorale_app_mobile/features/chants/chants_screen.dart';
import 'package:chorale_app_mobile/features/events/events_list_screen.dart';
import 'package:chorale_app_mobile/features/repetitions/repetitions_list_screen.dart';
import 'package:chorale_app_mobile/features/profile/profile_screen.dart';
import 'package:chorale_app_mobile/features/recorder/recorder_screen.dart';
import 'package:chorale_app_mobile/features/chants/chant_detail_screen.dart';

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
    const EventsListScreen(),
    const RepetitionsListScreen(),
    const ProfileScreen(),
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      drawer: _buildDrawer(context),
      body: _screens[_selectedIndex],
      bottomNavigationBar: Container(
        decoration: BoxDecoration(
          color: Colors.white,
          boxShadow: [
            BoxShadow(
              color: Colors.black.withAlpha(13),
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
              unselectedItemColor: Colors.blueGrey.shade300,
              selectedLabelStyle: const TextStyle(fontWeight: FontWeight.bold, fontSize: 12),
              unselectedLabelStyle: const TextStyle(fontWeight: FontWeight.w500, fontSize: 12),
              items: const [
                BottomNavigationBarItem(icon: Icon(Icons.home_rounded), label: 'Accueil'),
                BottomNavigationBarItem(icon: Icon(Icons.library_music_rounded), label: 'Chants'),
                BottomNavigationBarItem(icon: Icon(Icons.calendar_today_rounded), label: 'Agenda'),
                BottomNavigationBarItem(icon: Icon(Icons.repeat_rounded), label: 'Répet.'),
                BottomNavigationBarItem(icon: Icon(Icons.person_rounded), label: 'Profil'),
              ],
            ),
          ),
        ),
      ),
    );
  }

  Widget _buildDrawer(BuildContext context) {
    final user = Supabase.instance.client.auth.currentUser;
    final name = user?.userMetadata?['first_name'] ?? 'Choriste';
    final email = user?.email ?? '';

    return Drawer(
      child: Column(
        children: [
          UserAccountsDrawerHeader(
            decoration: const BoxDecoration(
              gradient: LinearGradient(
                colors: [Color(0xFF7367F0), Color(0xFF9E95F5)],
                begin: Alignment.topLeft,
                end: Alignment.bottomRight,
              ),
            ),
            accountName: Text(name, style: GoogleFonts.outfit(fontWeight: FontWeight.bold)),
            accountEmail: Text(email, style: GoogleFonts.outfit()),
            currentAccountPicture: const CircleAvatar(
              backgroundColor: Colors.white,
              child: Icon(Icons.person_rounded, color: Color(0xFF7367F0), size: 40),
            ),
          ),
          ListTile(
            leading: const Icon(Icons.home_outlined, color: Color(0xFF7367F0)),
            title: Text("Accueil", style: GoogleFonts.outfit()),
            onTap: () {
              setState(() => _selectedIndex = 0);
              Navigator.pop(context);
            },
          ),
          ListTile(
            leading: const Icon(Icons.library_music_outlined, color: Color(0xFF7367F0)),
            title: Text("Chants", style: GoogleFonts.outfit()),
            onTap: () {
              setState(() => _selectedIndex = 1);
              Navigator.pop(context);
            },
          ),
          ListTile(
            leading: const Icon(Icons.calendar_today_outlined, color: Color(0xFF7367F0)),
            title: Text("Agenda", style: GoogleFonts.outfit()),
            onTap: () {
              setState(() => _selectedIndex = 2);
              Navigator.pop(context);
            },
          ),
          ListTile(
            leading: const Icon(Icons.repeat_one_outlined, color: Color(0xFF7367F0)),
            title: Text("Répétitions", style: GoogleFonts.outfit()),
            onTap: () {
              setState(() => _selectedIndex = 3);
              Navigator.pop(context);
            },
          ),
          ListTile(
            leading: const Icon(Icons.person_outline_rounded, color: Color(0xFF7367F0)),
            title: Text("Profil", style: GoogleFonts.outfit()),
            onTap: () {
              setState(() => _selectedIndex = 4);
              Navigator.pop(context);
            },
          ),
          const Spacer(),
          const Divider(),
          ListTile(
            leading: const Icon(Icons.logout_rounded, color: Color(0xFFEA5455)),
            title: Text("Déconnexion", style: GoogleFonts.outfit(color: const Color(0xFFEA5455), fontWeight: FontWeight.w600)),
            onTap: () {
              Supabase.instance.client.auth.signOut();
            },
          ),
          const SizedBox(height: 20),
        ],
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
          expandedHeight: 180,
          floating: false,
          pinned: true,
          elevation: 0,
          backgroundColor: const Color(0xFF7367F0),
          flexibleSpace: FlexibleSpaceBar(
            background: Container(
              decoration: const BoxDecoration(
                gradient: LinearGradient(
                  colors: [Color(0xFF7367F0), Color(0xFF9E95F5)],
                  begin: Alignment.topLeft,
                  end: Alignment.bottomRight,
                ),
              ),
              child: Stack(
                children: [
                  Positioned(
                    right: -20,
                    bottom: -20,
                    child: Icon(Icons.music_note_rounded, size: 150, color: Colors.white.withAlpha(25)),
                  ),
                  Padding(
                    padding: const EdgeInsets.only(left: 20, bottom: 20),
                    child: Column(
                      mainAxisAlignment: MainAxisAlignment.end,
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          "Bonjour,",
                          style: GoogleFonts.outfit(color: Colors.white70, fontSize: 16),
                        ),
                        Text(
                          name,
                          style: GoogleFonts.outfit(
                            color: Colors.white,
                            fontSize: 28,
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                      ],
                    ),
                  ),
                ],
              ),
            ),
          ),
          actions: [
            IconButton(
              icon: const Icon(Icons.search_rounded, color: Colors.white),
              onPressed: () => showSearch(context: context, delegate: GlobalSearchDelegate()),
            ),
            const SizedBox(width: 8),
          ],
        ),
        SliverToBoxAdapter(
          child: Padding(
            padding: const EdgeInsets.fromLTRB(20, 25, 20, 0),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Row(
                  children: [
                    _buildStatCard("Chants", "124", Icons.library_music_rounded, const Color(0xFF7367F0)),
                    const SizedBox(width: 15),
                    _buildStatCard("Agenda", "12", Icons.calendar_today_rounded, const Color(0xFFEA5455)),
                  ],
                ),
                const SizedBox(height: 30),
                _buildSectionHeader("Prochain Événement"),
                const SizedBox(height: 15),
                _buildHighlightsCard(
                  "Veillée de Prière",
                  "Ce Samedi • 19:30",
                  "Cathédrale Saint Paul",
                  Icons.event_available_rounded,
                  const Color(0xFF28C76F),
                ),
                const SizedBox(height: 30),
                _buildSectionHeader("Outils Choriste"),
                const SizedBox(height: 15),
                _buildToolCard(
                  context,
                  "Enregistreur Vocal",
                  "Travaille tes partitions n'importe où",
                  Icons.mic_none_rounded,
                  const Color(0xFF7367F0),
                  const RecorderScreen(),
                ),
                const SizedBox(height: 30),
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
      style: GoogleFonts.outfit(
        fontSize: 18,
        fontWeight: FontWeight.bold,
        color: const Color(0xFF2F2B3D),
      ),
    );
  }

  Widget _buildStatCard(String label, String value, IconData icon, Color color) {
    return Expanded(
      child: Container(
        padding: const EdgeInsets.all(20),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(24),
          boxShadow: [
            BoxShadow(
              color: color.withAlpha(20),
              blurRadius: 15,
              offset: const Offset(0, 8),
            ),
          ],
        ),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Icon(icon, color: color, size: 28),
            const SizedBox(height: 15),
            Text(value, style: GoogleFonts.outfit(fontSize: 24, fontWeight: FontWeight.bold, color: const Color(0xFF2F2B3D))),
            Text(label, style: GoogleFonts.outfit(fontSize: 14, color: Colors.blueGrey.shade400)),
          ],
        ),
      ),
    );
  }

  Widget _buildHighlightsCard(String title, String time, String location, IconData icon, Color color) {
    return Container(
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(24),
        border: Border.all(color: Colors.blueGrey.shade50),
      ),
      child: Row(
        children: [
          Container(
            padding: const EdgeInsets.all(12),
            decoration: BoxDecoration(color: color.withAlpha(25), borderRadius: BorderRadius.circular(15)),
            child: Icon(icon, color: color, size: 28),
          ),
          const SizedBox(width: 15),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(title, style: GoogleFonts.outfit(fontWeight: FontWeight.bold, fontSize: 16, color: const Color(0xFF2F2B3D))),
                const SizedBox(height: 4),
                Text(time, style: TextStyle(color: color, fontSize: 13, fontWeight: FontWeight.w600)),
                const SizedBox(height: 2),
                Text(location, style: TextStyle(color: Colors.blueGrey.shade400, fontSize: 12)),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildToolCard(BuildContext context, String title, String subtitle, IconData icon, Color color, Widget screen) {
    return InkWell(
      onTap: () => Navigator.push(context, MaterialPageRoute(builder: (_) => screen)),
      borderRadius: BorderRadius.circular(24),
      child: Container(
        padding: const EdgeInsets.all(20),
        decoration: BoxDecoration(
          gradient: LinearGradient(
            colors: [color.withAlpha(200), color],
            begin: Alignment.topLeft,
            end: Alignment.bottomRight,
          ),
          borderRadius: BorderRadius.circular(24),
          boxShadow: [
            BoxShadow(
              color: color.withAlpha(80),
              blurRadius: 12,
              offset: const Offset(0, 6),
            ),
          ],
        ),
        child: Row(
          children: [
            const Icon(Icons.mic_rounded, color: Colors.white, size: 35),
            const SizedBox(width: 20),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(title, style: GoogleFonts.outfit(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 18)),
                  Text(subtitle, style: const TextStyle(color: Colors.white70, fontSize: 13)),
                ],
              ),
            ),
            const Icon(Icons.arrow_forward_ios_rounded, color: Colors.white, size: 16),
          ],
        ),
      ),
    );
  }
}

class GlobalSearchDelegate extends SearchDelegate {
  final _supabase = Supabase.instance.client;

  @override
  List<Widget>? buildActions(BuildContext context) {
    return [
      IconButton(
        icon: const Icon(Icons.clear_rounded),
        onPressed: () => query = "",
      ),
    ];
  }

  @override
  Widget? buildLeading(BuildContext context) {
    return IconButton(
      icon: const Icon(Icons.arrow_back_ios_new_rounded),
      onPressed: () => close(context, null),
    );
  }

  @override
  Widget buildResults(BuildContext context) {
    return _buildSearchResults();
  }

  @override
  Widget buildSuggestions(BuildContext context) {
    if (query.isEmpty) {
      return const Center(child: Text("Entrez un terme pour rechercher..."));
    }
    return _buildSearchResults();
  }

  Widget _buildSearchResults() {
    return FutureBuilder(
      future: Future.wait([
        _supabase.from('chants').select('id, title, composer, parole').ilike('title', '%$query%'),
        _supabase.from('events').select('id, title, start_at, location').ilike('title', '%$query%'),
      ]),
      builder: (context, AsyncSnapshot<List<dynamic>> snapshot) {
        if (snapshot.connectionState == ConnectionState.waiting) {
          return const Center(child: CircularProgressIndicator());
        }

        if (!snapshot.hasData || snapshot.data == null) {
          return const Center(child: Text("Aucun résultat trouvé."));
        }

        final chants = snapshot.data![0] as List<dynamic>;
        final events = snapshot.data![1] as List<dynamic>;

        if (chants.isEmpty && events.isEmpty) {
          return const Center(child: Text("Aucun résultat trouvé."));
        }

        return ListView(
          children: [
            if (chants.isNotEmpty) ...[
              const Padding(
                padding: EdgeInsets.all(15.0),
                child: Text("CHANTS", style: TextStyle(fontWeight: FontWeight.bold, color: Colors.grey, fontSize: 12)),
              ),
              ...chants.map((c) => ListTile(
                    leading: Container(
                      padding: const EdgeInsets.all(8),
                      decoration: BoxDecoration(
                        color: const Color(0xFF7367F0).withAlpha(25),
                        borderRadius: BorderRadius.circular(10),
                      ),
                      child: const Icon(Icons.music_note_rounded, color: Color(0xFF7367F0), size: 20),
                    ),
                    title: Text(c['title'], style: const TextStyle(fontWeight: FontWeight.bold)),
                    subtitle: Text(c['composer'] ?? "Compositeur inconnu"),
                    onTap: () {
                      close(context, null);
                      Navigator.push(context, MaterialPageRoute(builder: (_) => ChantDetailScreen(chant: c)));
                    },
                  )),
            ],
            if (events.isNotEmpty) ...[
              if (chants.isNotEmpty) const Divider(),
              const Padding(
                padding: EdgeInsets.all(15.0),
                child: Text("ÉVÉNEMENTS", style: TextStyle(fontWeight: FontWeight.bold, color: Colors.grey, fontSize: 12)),
              ),
              ...events.map((e) => ListTile(
                    leading: Container(
                      padding: const EdgeInsets.all(8),
                      decoration: BoxDecoration(
                        color: const Color(0xFFEA5455).withAlpha(25),
                        borderRadius: BorderRadius.circular(10),
                      ),
                      child: const Icon(Icons.event_rounded, color: Color(0xFFEA5455), size: 20),
                    ),
                    title: Text(e['title'], style: const TextStyle(fontWeight: FontWeight.bold)),
                    subtitle: Text(e['location'] ?? "Lieu non défini"),
                    onTap: () {
                      close(context, null);
                    },
                  )),
            ],
          ],
        );
      },
    );
  }
}
