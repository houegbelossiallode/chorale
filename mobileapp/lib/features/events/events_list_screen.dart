// lib/features/events/events_list_screen.dart
import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import '../../services/event_service.dart';
import '../../models/event.dart';
import 'package:intl/intl.dart';

class EventsListScreen extends StatefulWidget {
  const EventsListScreen({super.key});

  @override
  State<EventsListScreen> createState() => _EventsListScreenState();
}

class _EventsListScreenState extends State<EventsListScreen> {
  final EventService _eventService = EventService();
  late Future<List<Event>> _eventsFuture;

  @override
  void initState() {
    super.initState();
    _eventsFuture = _eventService.fetchEvents();
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
            backgroundColor: const Color(0xFF7367F0),
            flexibleSpace: FlexibleSpaceBar(
              title: Text(
                "Agenda & Événements",
                style: GoogleFonts.outfit(
                  fontWeight: FontWeight.bold,
                  fontSize: 18,
                  color: Colors.white,
                ),
              ),
              background: Container(
                decoration: const BoxDecoration(
                  gradient: LinearGradient(
                    colors: [Color(0xFF7367F0), Color(0xFF9E95F5)],
                    begin: Alignment.topLeft,
                    end: Alignment.bottomRight,
                  ),
                ),
              ),
            ),
          ),
          SliverFillRemaining(
            child: FutureBuilder<List<Event>>(
              future: _eventsFuture,
              builder: (context, snapshot) {
                if (snapshot.connectionState == ConnectionState.waiting) {
                  return const Center(child: CircularProgressIndicator());
                } else if (snapshot.hasError) {
                  return Center(child: Text("Erreur: ${snapshot.error}"));
                } else if (!snapshot.hasData || snapshot.data!.isEmpty) {
                  return const Center(child: Text("Aucun événement prévu."));
                }

                final events = snapshot.data!;
                return ListView.builder(
                  padding: const EdgeInsets.all(20),
                  itemCount: events.length,
                  itemBuilder: (context, index) {
                    final event = events[index];
                    return _buildEventCard(event);
                  },
                );
              },
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildEventCard(Event event) {
    final dateStr = DateFormat('EEEE dd MMMM • HH:mm', 'fr_FR').format(event.startDate);
    
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
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              Container(
                padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 5),
                decoration: BoxDecoration(
                  color: const Color(0xFF7367F0).withAlpha(25),
                  borderRadius: BorderRadius.circular(8),
                ),
                child: const Text(
                  "ÉVÉNEMENT",
                  style: TextStyle(
                    color: Color(0xFF7367F0),
                    fontSize: 10,
                    fontWeight: FontWeight.bold,
                  ),
                ),
              ),
            ],
          ),
          const SizedBox(height: 12),
          Text(
            event.title,
            style: GoogleFonts.outfit(
              fontWeight: FontWeight.bold,
              fontSize: 18,
              color: const Color(0xFF444050),
            ),
          ),
          const SizedBox(height: 10),
          Row(
            children: [
              Icon(Icons.calendar_today_rounded, size: 16, color: Colors.grey[400]),
              const SizedBox(width: 8),
              Text(
                dateStr,
                style: TextStyle(color: Colors.grey[600], fontSize: 13),
              ),
            ],
          ),
          if (event.location != null) ...[
            const SizedBox(height: 5),
            Row(
              children: [
                Icon(Icons.location_on_rounded, size: 16, color: Colors.grey[400]),
                const SizedBox(width: 8),
                Text(
                  event.location!,
                  style: TextStyle(color: Colors.grey[600], fontSize: 13),
                ),
              ],
            ),
          ],
          const SizedBox(height: 20),
          SizedBox(
            width: double.infinity,
            child: ElevatedButton.icon(
              onPressed: () {},
              icon: const Icon(Icons.description_outlined, size: 18),
              label: const Text("Voir le programme"),
              style: ElevatedButton.styleFrom(
                backgroundColor: const Color(0xFF7367F0),
                foregroundColor: Colors.white,
                elevation: 0,
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
