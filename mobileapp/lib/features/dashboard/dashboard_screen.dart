import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:supabase_flutter/supabase_flutter.dart';
import 'package:chorale_app_mobile/features/chants/chants_screen.dart';
import 'package:chorale_app_mobile/features/events/events_list_screen.dart';
import 'package:chorale_app_mobile/features/repetitions/repetitions_list_screen.dart';
import 'package:chorale_app_mobile/features/profile/profile_screen.dart';
import 'package:chorale_app_mobile/features/chants/chant_detail_screen.dart';
import 'package:chorale_app_mobile/features/notifications/notifications_screen.dart';
import '../../services/notification_service.dart';
import '../../services/dashboard_service.dart';
import '../../services/profile_service.dart';

class DashboardScreen extends StatefulWidget {
  const DashboardScreen({super.key});

  @override
  State<DashboardScreen> createState() => _DashboardScreenState();
}

class _DashboardScreenState extends State<DashboardScreen> {
  int _selectedIndex = 0;
  final _profileService = ProfileService();
  Map<String, dynamic>? _profile;
  bool _isLoadingProfile = true;

  void _onNavigate(int index) {
    if (mounted) {
      setState(() => _selectedIndex = index);
    }
  }

  late List<Widget> _screens;

  @override
  void initState() {
    super.initState();
    _loadProfile();
    _screens = [
      HomeScreen(onNavigate: _onNavigate, profile: _profile),
      const ChantsScreen(),
      const EventsListScreen(),
      const RepetitionsListScreen(),
      const ProfileScreen(),
    ];
  }

  Future<void> _loadProfile() async {
    if (mounted) setState(() => _isLoadingProfile = true);
    final profile = await _profileService.fetchProfile(forceRefresh: true);
    if (mounted) {
      setState(() {
        _profile = profile;
        _isLoadingProfile = false;
        // Update screens with new profile data
        _screens[0] = HomeScreen(onNavigate: _onNavigate, profile: _profile);
      });
    }
  }

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
            onTap: _updateIndex,
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

  void _updateIndex(int index) async {
    if (index == _selectedIndex) return;
    
    // If moving AWAY from profile, or returning TO something else, 
    // we might want to refresh if we suspect changes.
    // Specifically, if we were on Profile screen (index 4) and now moving away
    if (index == 4) {
      // Recreate ProfileScreen to force initState and refresh data
      _screens[4] = const ProfileScreen();
    }
    
    setState(() => _selectedIndex = index);
  }
   Widget _buildDrawer(BuildContext context) {
    final user = Supabase.instance.client.auth.currentUser;
    final name = _profile != null 
        ? "${_profile!['first_name'] ?? ''} ${_profile!['last_name'] ?? ''}".trim()
        : (user?.userMetadata?['first_name'] ?? 'Choriste');
    final email = _profile?['email'] ?? user?.email ?? '';
    final photoUrl = _profile?['photo_url'];

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
                  child: CircleAvatar(
                    radius: 32,
                    backgroundColor: Colors.white,
                    backgroundImage: photoUrl != null && photoUrl.toString().isNotEmpty
                        ? NetworkImage(photoUrl)
                        : const AssetImage('assets/logo.png') as ImageProvider,
                  ),
                ),
                const SizedBox(width: 15),
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        name.isEmpty ? 'Choriste' : name, 
                        style: GoogleFonts.outfit(fontWeight: FontWeight.bold, fontSize: 18, color: Colors.white),
                        maxLines: 1,
                        overflow: TextOverflow.ellipsis,
                      ),
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
        _updateIndex(index);
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
  final Function(int) onNavigate;
  final Map<String, dynamic>? profile;
  const HomeScreen({super.key, required this.onNavigate, this.profile});

  @override
  State<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  bool _isLoading = true;
  int _chantsAppris = 0;
  int _tauxPresence = 0;
  Map<String, dynamic>? _activiteRecente;
  Map<String, dynamic>? _chantDuMoment;
  List<dynamic> _derniersChants = [];

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
        _chantDuMoment = stats['chant_du_moment'];
        _derniersChants = stats['derniers_chants'] ?? [];
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
    final name = widget.profile != null 
        ? widget.profile!['first_name'] ?? 'Choriste' 
        : (user?.userMetadata?['first_name'] ?? 'Choriste');

    if (_isLoading) {
      return const Scaffold(body: Center(child: CircularProgressIndicator()));
    }

    return CustomScrollView(
      slivers: [
        _buildSliverAppBar(context),
        SliverToBoxAdapter(
          child: Padding(
            padding: const EdgeInsets.symmetric(horizontal: 25, vertical: 20),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                _buildWelcomeHeader(name),
                const SizedBox(height: 25),
                _buildStatsGrid(),
                const SizedBox(height: 35),
                
                if (_chantDuMoment != null)
                  _buildSectionTitle("Recommandation du jour", null),
                if (_chantDuMoment != null)
                  const SizedBox(height: 15),
                if (_chantDuMoment != null)
                  _buildChantDuMomentCard(_chantDuMoment!),
                if (_chantDuMoment != null)
                  const SizedBox(height: 35),

                _buildSectionTitle("Prochain Événement", () => widget.onNavigate(2)),
                const SizedBox(height: 15),
                if (_activiteRecente != null)
                  _buildHighlightsCard(
                    _activiteRecente!['titre'] ?? "Événement",
                    _activiteRecente!['jour_heure'] ?? "",
                    _activiteRecente!['lieu'] ?? "Lieu non défini",
                    Icons.calendar_today_rounded,
                    const Color(0xFF7367F0),
                  )
                else
                  _buildEmptyState("Aucun événement prévu pour le moment."),
                
                const SizedBox(height: 35),
                
                if (_derniersChants.isNotEmpty)
                  _buildSectionTitle("Derniers Chants Ajoutés", () => widget.onNavigate(1)),
                if (_derniersChants.isNotEmpty)
                  const SizedBox(height: 15),
                if (_derniersChants.isNotEmpty)
                  ..._derniersChants.map((c) => _buildMiniChantCard(c)).toList(),
                if (_derniersChants.isNotEmpty)
                  const SizedBox(height: 20),
                
                const SizedBox(height: 80),
              ],
            ),
          ),
        ),
      ],
    );
  }

  Widget _buildEmptyState(String message) {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(20),
        border: Border.all(color: Colors.blueGrey.shade50),
      ),
      child: Center(
        child: Text(
          message,
          textAlign: TextAlign.center,
          style: GoogleFonts.outfit(color: Colors.blueGrey[300], fontSize: 13),
        ),
      ),
    );
  }

  Widget _buildChantDuMomentCard(Map<String, dynamic> chant) {
    return Container(
      width: double.infinity,
      decoration: BoxDecoration(
        gradient: const LinearGradient(
          colors: [Color(0xFF7367F0), Color(0xFF9E95F5)],
          begin: Alignment.topLeft,
          end: Alignment.bottomRight,
        ),
        borderRadius: BorderRadius.circular(30),
        boxShadow: [
          BoxShadow(color: const Color(0xFF7367F0).withAlpha(60), blurRadius: 15, offset: const Offset(0, 10))
        ],
      ),
      child: Material(
        color: Colors.transparent,
        child: InkWell(
          borderRadius: BorderRadius.circular(30),
          onTap: () => Navigator.push(context, MaterialPageRoute(builder: (_) => ChantDetailScreen(chant: chant))),
          child: Padding(
            padding: const EdgeInsets.all(25),
            child: Row(
              children: [
                Container(
                  padding: const EdgeInsets.all(12),
                  decoration: BoxDecoration(color: Colors.white.withAlpha(50), shape: BoxShape.circle),
                  child: const Icon(Icons.music_note_rounded, color: Colors.white, size: 28),
                ),
                const SizedBox(width: 20),
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text("COUP DE CŒUR", style: GoogleFonts.outfit(color: Colors.white70, fontSize: 10, fontWeight: FontWeight.bold, letterSpacing: 1.2)),
                      const SizedBox(height: 4),
                      Text(chant['title'] ?? "Titre", style: GoogleFonts.outfit(color: Colors.white, fontSize: 18, fontWeight: FontWeight.bold)),
                      Text(chant['composer'] ?? "Compositeur", style: GoogleFonts.outfit(color: Colors.white70, fontSize: 13)),
                    ],
                  ),
                ),
                const Icon(Icons.play_circle_fill_rounded, color: Colors.white, size: 40),
              ],
            ),
          ),
        ),
      ),
    );
  }

  Widget _buildMiniChantCard(Map<String, dynamic> chant) {
    return Container(
      margin: const EdgeInsets.only(bottom: 12),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(20),
        border: Border.all(color: Colors.blueGrey.shade50),
      ),
      child: ListTile(
        contentPadding: const EdgeInsets.symmetric(horizontal: 15, vertical: 5),
        leading: Container(
          padding: const EdgeInsets.all(8),
          decoration: BoxDecoration(color: const Color(0xFF7367F0).withAlpha(15), borderRadius: BorderRadius.circular(12)),
          child: const Icon(Icons.library_music_rounded, color: Color(0xFF7367F0), size: 20),
        ),
        title: Text(chant['title'] ?? "", style: GoogleFonts.outfit(fontWeight: FontWeight.bold, fontSize: 14, color: const Color(0xFF444050))),
        subtitle: Text(chant['composer'] ?? "Inconnu", style: GoogleFonts.outfit(fontSize: 12, color: Colors.blueGrey[300])),
        trailing: const Icon(Icons.chevron_right_rounded, color: Color(0xFFD0D2D6)),
        onTap: () => Navigator.push(context, MaterialPageRoute(builder: (_) => ChantDetailScreen(chant: chant))),
      ),
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

  Widget _buildSectionTitle(String title, VoidCallback? onTap) {
    return Row(
      mainAxisAlignment: MainAxisAlignment.spaceBetween,
      children: [
        Text(title, style: GoogleFonts.outfit(fontSize: 18, fontWeight: FontWeight.bold, color: const Color(0xFF444050))),
        if (onTap != null)
          InkWell(
            onTap: onTap,
            borderRadius: BorderRadius.circular(10),
            child: Padding(
              padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
              child: Text("Voir tout", style: GoogleFonts.outfit(fontSize: 12, color: const Color(0xFF7367F0), fontWeight: FontWeight.bold)),
            ),
          ),
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
    return _buildSearchResults(context);
  }

  @override
  Widget buildSuggestions(BuildContext context) {
    if (query.isEmpty) {
      return const Center(child: Text("Entrez un terme pour rechercher..."));
    }
    return _buildSearchResults(context);
  }

  Widget _buildSearchResults(BuildContext context) {
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
