import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:supabase_flutter/supabase_flutter.dart';
import 'package:chorale_app_mobile/features/chants/chants_screen.dart';
import 'package:chorale_app_mobile/features/events/events_list_screen.dart';
import 'package:chorale_app_mobile/features/repetitions/repetitions_list_screen.dart';
import 'package:chorale_app_mobile/features/profile/profile_screen.dart';
import 'package:chorale_app_mobile/features/recorder/recorder_screen.dart';
import 'package:chorale_app_mobile/features/chants/chant_detail_screen.dart';
import 'package:chorale_app_mobile/features/notifications/notifications_screen.dart';
import '../../services/notification_service.dart';
import '../../services/dashboard_service.dart';

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
      backgroundColor: const Color(0xFFFAFAFE),
      drawer: _buildDrawer(context),
      body: _screens[_selectedIndex],
      bottomNavigationBar: _buildBottomBar(),
    );
  }

  Widget _buildBottomBar() {
    return Container(
      decoration: BoxDecoration(
        color: Colors.white,
        boxShadow: [
          BoxShadow(
            color: Colors.black.withAlpha(10),
            blurRadius: 30,
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
            unselectedItemColor: const Color(0xFFB9B9C3),
            selectedLabelStyle: GoogleFonts.outfit(fontWeight: FontWeight.bold, fontSize: 11),
            unselectedLabelStyle: GoogleFonts.outfit(fontWeight: FontWeight.w500, fontSize: 11),
            items: const [
              BottomNavigationBarItem(icon: Icon(Icons.grid_view_rounded), label: 'Accueil'),
              BottomNavigationBarItem(icon: Icon(Icons.library_music_rounded), label: 'Chants'),
              BottomNavigationBarItem(icon: Icon(Icons.calendar_today_rounded), label: 'Agenda'),
              BottomNavigationBarItem(icon: Icon(Icons.auto_awesome_motion_rounded), label: 'Répét.'),
              BottomNavigationBarItem(icon: Icon(Icons.person_rounded), label: 'Profil'),
            ],
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
      backgroundColor: Colors.white,
      child: Column(
        children: [
          Container(
            padding: const EdgeInsets.fromLTRB(20, 60, 20, 30),
            decoration: const BoxDecoration(
              gradient: LinearGradient(
                colors: [Color(0xFF7367F0), Color(0xFF9E95F5)],
                begin: Alignment.topLeft,
                end: Alignment.bottomRight,
              ),
            ),
            child: Row(
              children: [
                Container(
                  padding: const EdgeInsets.all(2),
                  decoration: const BoxDecoration(color: Colors.white, shape: BoxShape.circle),
                  child: const CircleAvatar(
                    radius: 32,
                    backgroundColor: Colors.white,
                    backgroundImage: AssetImage('assets/logo.png'),
                  ),
                ),
                const SizedBox(width: 15),
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(name, style: GoogleFonts.outfit(fontWeight: FontWeight.bold, fontSize: 18, color: Colors.white)),
                      Text(email, style: GoogleFonts.outfit(fontSize: 12, color: Colors.white70)),
                    ],
                  ),
                ),
              ],
            ),
          ),
          const SizedBox(height: 20),
          _buildDrawerItem(Icons.home_rounded, "Tableau de Bord", 0),
          _buildDrawerItem(Icons.library_music_rounded, "Bibliothèque des Chants", 1),
          _buildDrawerItem(Icons.calendar_today_rounded, "Agenda & Programme", 2),
          _buildDrawerItem(Icons.repeat_rounded, "Répétitions", 3),
          _buildDrawerItem(Icons.person_rounded, "Mon Profil", 4),
          const Spacer(),
          const Divider(indent: 20, endIndent: 20),
          ListTile(
            leading: const Icon(Icons.logout_rounded, color: Color(0xFFEA5455)),
            title: Text("Se déconnecter", style: GoogleFonts.outfit(color: const Color(0xFFEA5455), fontWeight: FontWeight.bold)),
            onTap: () => Supabase.instance.client.auth.signOut(),
          ),
          const SizedBox(height: 30),
        ],
      ),
    );
  }

  Widget _buildDrawerItem(IconData icon, String title, int index) {
    final isSelected = _selectedIndex == index;
    return ListTile(
      leading: Icon(icon, color: isSelected ? const Color(0xFF7367F0) : const Color(0xFF444050)),
      title: Text(title, style: GoogleFonts.outfit(fontWeight: isSelected ? FontWeight.bold : FontWeight.w500, color: isSelected ? const Color(0xFF7367F0) : const Color(0xFF444050))),
      onTap: () {
        setState(() => _selectedIndex = index);
        Navigator.pop(context);
      },
      selected: isSelected,
      selectedTileColor: const Color(0xFF7367F0).withAlpha(15),
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
      contentPadding: const EdgeInsets.symmetric(horizontal: 20),
    );
  }
}

class HomeScreen extends StatefulWidget {
  const HomeScreen({super.key});

  @override
  State<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  bool _isLoading = true;
  int _chantsAppris = 0;
  int _tauxPresence = 0;
  Map<String, dynamic>? _activiteRecente;

  @override
  void initState() {
    super.initState();
    _loadDashboardData();
  }

  Future<void> _loadDashboardData() async {
    final stats = await DashboardService().fetchDashboardStats();
    if (stats != null && mounted) {
      setState(() {
        _chantsAppris = stats['chants_appris'] ?? 0;
        _tauxPresence = stats['taux_presence'] ?? 0;
        _activiteRecente = stats['activite_recente'];
        _isLoading = false;
      });
    } else if (mounted) {
      setState(() {
        _isLoading = false;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    final user = Supabase.instance.client.auth.currentUser;
    final name = user?.userMetadata?['first_name'] ?? 'Choriste';

    if (_isLoading) {
      return const Scaffold(body: Center(child: CircularProgressIndicator()));
    }

    return CustomScrollView(
      slivers: [
        _buildSliverAppBar(context),
        SliverToBoxAdapter(
          child: Padding(
            padding: const EdgeInsets.all(25),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                _buildWelcomeHeader(name),
                const SizedBox(height: 30),
                _buildStatsGrid(),
                const SizedBox(height: 40),
                if (_activiteRecente != null) ...[
                  _buildSectionTitle("Activités Récentes"),
                  const SizedBox(height: 20),
                  _buildHighlightsCard(
                    _activiteRecente!['titre'] ?? "Événement",
                    _activiteRecente!['jour_heure'] ?? "",
                    _activiteRecente!['lieu'] ?? "Lieu non défini",
                    Icons.event_available_rounded,
                    const Color(0xFFC9A84C), // Dynamique potentiellement
                  ),
                  const SizedBox(height: 40),
                ] else ...[
                  _buildSectionTitle("Activités Récentes"),
                  const SizedBox(height: 20),
                  Center(child: Text("Aucune activité prévue pour le moment.", style: GoogleFonts.outfit(color: Colors.blueGrey[300]))),
                  const SizedBox(height: 40),
                ],
                _buildSectionTitle("Outils Privilégiés"),
                const SizedBox(height: 20),
                _buildPremiumToolCard(
                  context,
                  "Enregistreur Magique",
                  "Enregistre tes meilleures vocalises",
                  Icons.mic_external_on_rounded,
                  const Color(0xFF7367F0),
                  const RecorderScreen(),
                ),
                const SizedBox(height: 15),
                _buildPremiumToolCard(
                  context,
                  "Ma Caisse Chorale",
                  "Gère tes cotisations en un clic",
                  Icons.account_balance_wallet_rounded,
                  const Color(0xFF28C76F),
                  const ChantsScreen(),
                ),
                const SizedBox(height: 100),
              ],
            ),
          ),
        ),
      ],
    );
  }

  Widget _buildSliverAppBar(BuildContext context) {
    return SliverAppBar(
      expandedHeight: 0,
      pinned: true,
      elevation: 0,
      backgroundColor: Colors.white,
      foregroundColor: const Color(0xFF444050),
      leading: IconButton(
        icon: const Icon(Icons.notes_rounded),
        onPressed: () => Scaffold.of(context).openDrawer(),
      ),
      title: Text("Dashboard", style: GoogleFonts.outfit(fontWeight: FontWeight.bold, fontSize: 18)),
      centerTitle: true,
      actions: [
        StreamBuilder(
          stream: Stream.periodic(const Duration(seconds: 30)).asyncMap((_) => NotificationService().fetchNotifications()),
          builder: (context, snapshot) {
            final notifications = snapshot.data ?? [];
            final unreadCount = notifications.where((n) => n['read_at'] == null).length;

            return Stack(
              alignment: Alignment.center,
              children: [
                IconButton(
                  icon: const Icon(Icons.notifications_none_rounded),
                  onPressed: () => Navigator.push(context, MaterialPageRoute(builder: (_) => const NotificationsScreen())),
                ),
                if (unreadCount > 0)
                  Positioned(
                    right: 8,
                    top: 8,
                    child: Container(
                      padding: const EdgeInsets.all(4),
                      decoration: const BoxDecoration(color: Color(0xFFEA5455), shape: BoxShape.circle),
                      constraints: const BoxConstraints(minWidth: 16, minHeight: 16),
                      child: Text(
                        unreadCount.toString(),
                        style: const TextStyle(color: Colors.white, fontSize: 8, fontWeight: FontWeight.bold),
                        textAlign: TextAlign.center,
                      ),
                    ),
                  ),
              ],
            );
          },
        ),
        const SizedBox(width: 10),
      ],
    );
  }

  Widget _buildWelcomeHeader(String name) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          "Bonjour $name,",
          style: GoogleFonts.outfit(fontSize: 26, fontWeight: FontWeight.bold, color: const Color(0xFF444050)),
        ),
        const SizedBox(height: 5),
        Text(
          "Prêt pour une nouvelle répétition ?",
          style: GoogleFonts.outfit(fontSize: 14, color: Colors.blueGrey[300], fontWeight: FontWeight.w500),
        ),
      ],
    );
  }

  Widget _buildStatsGrid() {
    return Row(
      children: [
        _buildStatCard("Chants Appris", "$_chantsAppris", Icons.auto_awesome_rounded, const Color(0xFF7367F0)),
        const SizedBox(width: 20),
        _buildStatCard("Présences", "$_tauxPresence%", Icons.verified_user_rounded, const Color(0xFF28C76F)),
      ],
    );
  }

  Widget _buildStatCard(String label, String value, IconData icon, Color color) {
    return Expanded(
      child: Container(
        padding: const EdgeInsets.all(25),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(30),
          boxShadow: [
            BoxShadow(
              color: color.withAlpha(15),
              blurRadius: 20,
              offset: const Offset(0, 10),
            ),
          ],
        ),
        child: Column(
          children: [
            Container(
              padding: const EdgeInsets.all(12),
              decoration: BoxDecoration(color: color.withAlpha(25), shape: BoxShape.circle),
              child: Icon(icon, color: color, size: 24),
            ),
            const SizedBox(height: 20),
            Text(value, style: GoogleFonts.outfit(fontSize: 24, fontWeight: FontWeight.bold, color: const Color(0xFF444050))),
            Text(label, style: GoogleFonts.outfit(fontSize: 12, color: Colors.blueGrey[300], fontWeight: FontWeight.bold)),
          ],
        ),
      ),
    );
  }

  Widget _buildSectionTitle(String title) {
    return Row(
      mainAxisAlignment: MainAxisAlignment.spaceBetween,
      children: [
        Text(title, style: GoogleFonts.outfit(fontSize: 18, fontWeight: FontWeight.bold, color: const Color(0xFF444050))),
        Text("Voir tout", style: GoogleFonts.outfit(fontSize: 12, color: const Color(0xFF7367F0), fontWeight: FontWeight.bold)),
      ],
    );
  }

  Widget _buildHighlightsCard(String title, String time, String location, IconData icon, Color color) {
    return Container(
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(30),
        border: Border.all(color: Colors.blueGrey.shade50),
      ),
      child: Row(
        children: [
          Container(
            height: 60,
            width: 60,
            decoration: BoxDecoration(
              gradient: LinearGradient(colors: [color, color.withAlpha(150)]),
              borderRadius: BorderRadius.circular(20),
            ),
            child: Icon(icon, color: Colors.white, size: 28),
          ),
          const SizedBox(width: 20),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(title, style: GoogleFonts.outfit(fontWeight: FontWeight.bold, fontSize: 16, color: const Color(0xFF444050))),
                const SizedBox(height: 5),
                Row(
                  children: [
                    Icon(Icons.access_time_rounded, size: 14, color: color),
                    const SizedBox(width: 5),
                    Text(time, style: GoogleFonts.outfit(color: color, fontSize: 12, fontWeight: FontWeight.bold)),
                  ],
                ),
                Text(location, style: GoogleFonts.outfit(color: Colors.blueGrey[300], fontSize: 12)),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildPremiumToolCard(BuildContext context, String title, String subtitle, IconData icon, Color color, Widget screen) {
    return Container(
      decoration: BoxDecoration(
        borderRadius: BorderRadius.circular(30),
        boxShadow: [
          BoxShadow(
            color: color.withAlpha(40),
            blurRadius: 15,
            offset: const Offset(0, 8),
          ),
        ],
      ),
      child: ClipRRect(
        borderRadius: BorderRadius.circular(30),
        child: Material(
          color: Colors.white,
          child: InkWell(
            onTap: () => Navigator.push(context, MaterialPageRoute(builder: (_) => screen)),
            child: Padding(
              padding: const EdgeInsets.all(25),
              child: Row(
                children: [
                  Container(
                    padding: const EdgeInsets.all(15),
                    decoration: BoxDecoration(color: color.withAlpha(25), borderRadius: BorderRadius.circular(20)),
                    child: Icon(icon, color: color, size: 30),
                  ),
                  const SizedBox(width: 20),
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(title, style: GoogleFonts.outfit(fontWeight: FontWeight.bold, fontSize: 17, color: const Color(0xFF444050))),
                        Text(subtitle, style: GoogleFonts.outfit(fontSize: 12, color: Colors.blueGrey[300])),
                      ],
                    ),
                  ),
                  const Icon(Icons.chevron_right_rounded, color: Color(0xFFD0D2D6)),
                ],
              ),
            ),
          ),
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
          padding: const EdgeInsets.all(20),
          children: [
            if (chants.isNotEmpty) ...[
              Text("CHANTS", style: GoogleFonts.outfit(fontWeight: FontWeight.bold, color: Colors.grey, fontSize: 12)),
              const SizedBox(height: 10),
              ...chants.map((c) => Card(
                    margin: const EdgeInsets.only(bottom: 10),
                    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(15)),
                    child: ListTile(
                      leading: const Icon(Icons.music_note_rounded, color: Color(0xFF7367F0)),
                      title: Text(c['title'], style: const TextStyle(fontWeight: FontWeight.bold)),
                      subtitle: Text(c['composer'] ?? "Compositeur inconnu"),
                      onTap: () {
                        close(context, null);
                        Navigator.push(context, MaterialPageRoute(builder: (_) => ChantDetailScreen(chant: c)));
                      },
                    ),
                  )),
            ],
            if (events.isNotEmpty) ...[
              const SizedBox(height: 20),
              Text("ÉVÉNEMENTS", style: GoogleFonts.outfit(fontWeight: FontWeight.bold, color: Colors.grey, fontSize: 12)),
              const SizedBox(height: 10),
              ...events.map((e) => Card(
                    margin: const EdgeInsets.only(bottom: 10),
                    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(15)),
                    child: ListTile(
                      leading: const Icon(Icons.event_rounded, color: Color(0xFFEA5455)),
                      title: Text(e['title'], style: const TextStyle(fontWeight: FontWeight.bold)),
                      subtitle: Text(e['location'] ?? "Lieu non défini"),
                      onTap: () => close(context, null),
                    ),
                  )),
            ],
          ],
        );
      },
    );
  }
}
