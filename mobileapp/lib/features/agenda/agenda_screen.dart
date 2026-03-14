import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';

class AgendaScreen extends StatelessWidget {
  const AgendaScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return CustomScrollView(
      slivers: [
        SliverAppBar(
          expandedHeight: 100,
          floating: false,
          pinned: true,
          backgroundColor: Colors.white,
          elevation: 0,
          flexibleSpace: FlexibleSpaceBar(
            titlePadding: const EdgeInsets.symmetric(horizontal: 20, vertical: 16),
            title: Text(
              "Agenda",
              style: GoogleFonts.outfit(fontWeight: FontWeight.bold, fontSize: 10, color: const Color(0xFF444050)),
            ),
          ),
        ),
        SliverToBoxAdapter(
          child: Padding(
            padding: const EdgeInsets.all(20),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                _buildAgendaItem(
                  "Répétition Générale",
                  "Samedi 15 Mars • 16:00",
                  "Paroisse St Michel",
                  const Color(0xFF7367F0),
                  isPast: false,
                ),
                const SizedBox(height: 15),
                _buildAgendaItem(
                  "Messe de Pâques",
                  "Dimanche 31 Mars • 09:00",
                  "Cathédrale",
                  const Color(0xFFEA5455),
                  isPast: false,
                ),
                const SizedBox(height: 15),
                _buildAgendaItem(
                  "Concert de Noël",
                  "Mercredi 25 Décembre • 19:00",
                  "St Joseph",
                  Colors.blueGrey,
                  isPast: true,
                ),
              ],
            ),
          ),
        ),
      ],
    );
  }

  Widget _buildAgendaItem(String title, String date, String location, Color color, {bool isPast = false}) {
    return Container(
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(24),
        border: Border.all(color: Colors.blueGrey.shade100),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              const Spacer(),
              const Icon(Icons.more_horiz, color: Colors.blueGrey),
            ],
          ),
          const SizedBox(height: 15),
          Text(
            title,
            style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 13, color: Color(0xFF444050)),
          ),
          const SizedBox(height: 10),
          Row(
            children: [
              Icon(Icons.calendar_today_rounded, size: 16, color: Colors.blueGrey.shade400),
              const SizedBox(width: 8),
              Text(date, style: TextStyle(color: Colors.blueGrey.shade500, fontSize: 14)),
            ],
          ),
          const SizedBox(height: 6),
          Row(
            children: [
              Icon(Icons.location_on_rounded, size: 16, color: Colors.blueGrey.shade400),
              const SizedBox(width: 8),
              Text(location, style: TextStyle(color: Colors.blueGrey.shade500, fontSize: 14)),
            ],
          ),
          if (!isPast) ...[
            const SizedBox(height: 10),
            const Divider(height: 1),
            const SizedBox(height: 15),
            Text(
              "Seriez-vous présent ?",
              style: GoogleFonts.outfit(fontSize: 12, fontWeight: FontWeight.bold, color: Colors.blueGrey.shade600),
            ),
            const SizedBox(height: 10),
            Row(
              children: [
                _buildPresenceChip("Oui", Colors.green),
                const SizedBox(width: 8),
                _buildPresenceChip("Non", Colors.red),
                const SizedBox(width: 8),
                _buildPresenceChip("Peut-être", Colors.orange),
              ],
            ),
          ],
          const SizedBox(height: 20),
          if (!isPast)
            SizedBox(
              width: double.infinity,
              child: ElevatedButton(
                onPressed: () {},
                style: ElevatedButton.styleFrom(
                  backgroundColor: color,
                  foregroundColor: Colors.white,
                  elevation: 0,
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(15)),
                  padding: const EdgeInsets.symmetric(vertical: 12),
                ),
                child: const Text("Voir le programme", style: TextStyle(fontWeight: FontWeight.bold)),
              ),
            ),
        ],
      ),
    );
  }

  Widget _buildPresenceChip(String label, Color color) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(12),
        border: Border.all(color: color.withAlpha(50)),
        boxShadow: [
          BoxShadow(color: color.withAlpha(15), blurRadius: 4, offset: const Offset(0, 2)),
        ],
      ),
      child: Text(
        label,
        style: GoogleFonts.outfit(fontSize: 11, fontWeight: FontWeight.bold, color: color),
      ),
    );
  }
}
