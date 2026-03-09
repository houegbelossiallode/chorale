// lib/features/repetitions/repetitions_list_screen.dart
import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import '../../services/repetition_service.dart';
import '../../models/repetition.dart';
import 'package:intl/intl.dart';

class RepetitionsListScreen extends StatefulWidget {
  const RepetitionsListScreen({super.key});

  @override
  State<RepetitionsListScreen> createState() => _RepetitionsListScreenState();
}

class _RepetitionsListScreenState extends State<RepetitionsListScreen> {
  final RepetitionService _repetitionService = RepetitionService();
  late Future<List<Repetition>> _repetitionsFuture;

  @override
  void initState() {
    super.initState();
    _repetitionsFuture = _repetitionService.fetchRepetitions();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.grey[50],
      body: CustomScrollView(
        slivers: [
          SliverAppBar(
            expandedHeight: 120,
            floating: false,
            pinned: true,
            backgroundColor: const Color(0xFF28C76F),
            flexibleSpace: FlexibleSpaceBar(
              title: Text(
                "Répétitions",
                style: GoogleFonts.outfit(
                  fontWeight: FontWeight.bold,
                  fontSize: 18,
                  color: Colors.white,
                ),
              ),
              background: Container(
                decoration: const BoxDecoration(
                  gradient: LinearGradient(
                    colors: [Color(0xFF28C76F), Color(0xFF48DA89)],
                    begin: Alignment.topLeft,
                    end: Alignment.bottomRight,
                  ),
                ),
              ),
            ),
          ),
          SliverFillRemaining(
            child: FutureBuilder<List<Repetition>>(
              future: _repetitionsFuture,
              builder: (context, snapshot) {
                if (snapshot.connectionState == ConnectionState.waiting) {
                  return const Center(child: CircularProgressIndicator());
                } else if (snapshot.hasError) {
                  return Center(child: Text("Erreur: ${snapshot.error}"));
                } else if (!snapshot.hasData || snapshot.data!.isEmpty) {
                  return const Center(child: Text("Aucune répétition enregistrée."));
                }

                final repetitions = snapshot.data!;
                return ListView.builder(
                  padding: const EdgeInsets.all(20),
                  itemCount: repetitions.length,
                  itemBuilder: (context, index) {
                    final repetition = repetitions[index];
                    return _buildRepetitionCard(repetition);
                  },
                );
              },
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildRepetitionCard(Repetition repetition) {
    final dateStr = DateFormat('EEEE dd MMMM • HH:mm', 'fr_FR').format(repetition.date);
    
    return Container(
      margin: const EdgeInsets.only(bottom: 15),
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(24),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withAlpha(8),
            blurRadius: 10,
            offset: const Offset(0, 4),
          ),
        ],
      ),
      child: Column(
        children: [
          Row(
            children: [
              Container(
                padding: const EdgeInsets.all(12),
                decoration: BoxDecoration(
                  color: const Color(0xFF28C76F).withAlpha(25),
                  borderRadius: BorderRadius.circular(15),
                ),
                child: const Icon(
                  Icons.event_repeat_rounded,
                  color: Color(0xFF28C76F),
                ),
              ),
              const SizedBox(width: 15),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      repetition.title ?? 'Répétition sans titre',
                      style: GoogleFonts.outfit(
                        fontWeight: FontWeight.bold,
                        fontSize: 16,
                        color: const Color(0xFF444050),
                      ),
                    ),
                    const SizedBox(height: 5),
                    Text(
                      dateStr,
                      style: TextStyle(color: Colors.grey[600], fontSize: 13),
                    ),
                  ],
                ),
              ),
            ],
          ),
          const SizedBox(height: 15),
          SizedBox(
            width: double.infinity,
            child: OutlinedButton.icon(
              onPressed: () {},
              icon: const Icon(Icons.library_music_outlined, size: 18),
              label: const Text("Voir le répertoire"),
              style: OutlinedButton.styleFrom(
                foregroundColor: const Color(0xFF28C76F),
                side: const BorderSide(color: Color(0xFF28C76F)),
                padding: const EdgeInsets.symmetric(vertical: 12),
                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
              ),
            ),
          ),
        ],
      ),
    );
  }
}
