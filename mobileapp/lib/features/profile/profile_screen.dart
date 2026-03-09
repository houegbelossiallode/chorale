// lib/features/profile/profile_screen.dart
import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:supabase_flutter/supabase_flutter.dart';
import '../../services/profile_service.dart';

class ProfileScreen extends StatefulWidget {
  const ProfileScreen({super.key});

  @override
  State<ProfileScreen> createState() => _ProfileScreenState();
}

class _ProfileScreenState extends State<ProfileScreen> {
  final ProfileService _profileService = ProfileService();
  late Future<Map<String, dynamic>?> _profileFuture;
  bool _isEditing = false;
  bool _isSaving = false;

  final TextEditingController _firstNameController = TextEditingController();
  final TextEditingController _lastNameController = TextEditingController();
  final TextEditingController _phoneController = TextEditingController();
  final TextEditingController _addressController = TextEditingController();

  @override
  void initState() {
    super.initState();
    _loadProfile();
  }

  void _loadProfile() {
    _profileFuture = _profileService.fetchProfile();
    _profileFuture.then((profile) {
      if (profile != null) {
        _firstNameController.text = profile['first_name'] ?? '';
        _lastNameController.text = profile['last_name'] ?? '';
        _phoneController.text = profile['phone'] ?? '';
        _addressController.text = profile['address'] ?? '';
      }
    });
  }

  @override
  void dispose() {
    _firstNameController.dispose();
    _lastNameController.dispose();
    _phoneController.dispose();
    _addressController.dispose();
    super.dispose();
  }

  Future<void> _saveProfile() async {
    setState(() => _isSaving = true);
    try {
      await _profileService.updateProfile({
        'first_name': _firstNameController.text,
        'last_name': _lastNameController.text,
        'phone': _phoneController.text,
        'address': _addressController.text,
      });
      setState(() => _isEditing = false);
      _loadProfile();
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text("Profil mis à jour avec succès"), backgroundColor: Colors.green),
        );
      }
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text("Erreur: $e"), backgroundColor: Colors.red),
        );
      }
    } finally {
      if (mounted) setState(() => _isSaving = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    final user = Supabase.instance.client.auth.currentUser;

    return Scaffold(
      backgroundColor: Colors.grey[50],
      appBar: AppBar(
        title: Text("Mon Profil", style: GoogleFonts.outfit(fontWeight: FontWeight.bold)),
        backgroundColor: Colors.white,
        foregroundColor: const Color(0xFF444050),
        elevation: 0,
        actions: [
          if (_isEditing)
            IconButton(
              icon: _isSaving ? const SizedBox(width: 20, height: 20, child: CircularProgressIndicator(strokeWidth: 2)) : const Icon(Icons.check_rounded, color: Colors.green),
              onPressed: _isSaving ? null : _saveProfile,
            )
          else
            IconButton(
              icon: const Icon(Icons.logout_rounded, color: Color(0xFFEA5455)),
              onPressed: () => Supabase.instance.client.auth.signOut(),
            ),
        ],
      ),
      body: FutureBuilder<Map<String, dynamic>?>(
        future: _profileFuture,
        builder: (context, snapshot) {
          if (snapshot.connectionState == ConnectionState.waiting && !_isEditing) {
            return const Center(child: CircularProgressIndicator());
          }

          final profile = snapshot.data;
          final email = user?.email ?? '';

          return SingleChildScrollView(
            padding: const EdgeInsets.all(25),
            child: Column(
              children: [
                const CircleAvatar(
                  radius: 50,
                  backgroundColor: Color(0xFF7367F0),
                  child: Icon(Icons.person_rounded, size: 50, color: Colors.white),
                ),
                const SizedBox(height: 20),
                if (!_isEditing) ...[
                  Text(
                    "${_firstNameController.text} ${_lastNameController.text}",
                    style: GoogleFonts.outfit(fontSize: 22, fontWeight: FontWeight.bold, color: const Color(0xFF444050)),
                  ),
                  Text(
                    email,
                    style: TextStyle(color: Colors.grey[600], fontSize: 14),
                  ),
                ],
                const SizedBox(height: 40),
                if (_isEditing) ...[
                  _buildEditField("Prénom", _firstNameController, Icons.person_outline),
                  _buildEditField("Nom", _lastNameController, Icons.person_outline),
                  _buildEditField("Contact (Téléphone)", _phoneController, Icons.phone_rounded),
                  _buildEditField("Adresse", _addressController, Icons.location_on_rounded),
                ] else ...[
                  _buildProfileItem(Icons.mic_rounded, "Pupitre", profile?['pupitre'] ?? "Non défini"),
                  _buildProfileItem(Icons.phone_rounded, "Contact", profile?['phone'] ?? "Non renseigné"),
                  _buildProfileItem(Icons.location_on_rounded, "Adresse", profile?['address'] ?? "Non renseignée"),
                ],
                const SizedBox(height: 40),
                SizedBox(
                  width: double.infinity,
                  child: OutlinedButton(
                    onPressed: () {
                      setState(() => _isEditing = !_isEditing);
                    },
                    style: OutlinedButton.styleFrom(
                      side: BorderSide(color: _isEditing ? Colors.grey : const Color(0xFF7367F0)),
                      padding: const EdgeInsets.symmetric(vertical: 15),
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(15)),
                    ),
                    child: Text(
                      _isEditing ? "Annuler" : "Modifier le profil",
                      style: TextStyle(fontWeight: FontWeight.bold, color: _isEditing ? Colors.grey : const Color(0xFF7367F0)),
                    ),
                  ),
                ),
              ],
            ),
          );
        },
      ),
    );
  }

  Widget _buildProfileItem(IconData icon, String title, String value) {
    return Container(
      margin: const EdgeInsets.only(bottom: 15),
      padding: const EdgeInsets.all(15),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(18),
        border: Border.all(color: Colors.grey.shade200),
      ),
      child: Row(
        children: [
          Icon(icon, color: const Color(0xFF7367F0), size: 20),
          const SizedBox(width: 15),
          Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(title, style: TextStyle(color: Colors.grey[500], fontSize: 12)),
              Text(value, style: const TextStyle(fontWeight: FontWeight.bold, color: Color(0xFF444050))),
            ],
          ),
        ],
      ),
    );
  }

  Widget _buildEditField(String label, TextEditingController controller, IconData icon) {
    return Container(
      margin: const EdgeInsets.only(bottom: 15),
      child: TextFormField(
        controller: controller,
        decoration: InputDecoration(
          labelText: label,
          prefixIcon: Icon(icon, color: const Color(0xFF7367F0), size: 20),
          border: OutlineInputBorder(borderRadius: BorderRadius.circular(15)),
          enabledBorder: OutlineInputBorder(
            borderRadius: BorderRadius.circular(15),
            borderSide: BorderSide(color: Colors.grey.shade300),
          ),
          focusedBorder: OutlineInputBorder(
            borderRadius: BorderRadius.circular(15),
            borderSide: const BorderSide(color: Color(0xFF7367F0)),
          ),
        ),
      ),
    );
  }
}
