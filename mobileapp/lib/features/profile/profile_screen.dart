import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:supabase_flutter/supabase_flutter.dart';
import 'package:image_picker/image_picker.dart';
import 'dart:io';
import '../../services/profile_service.dart';
import 'package:intl/intl.dart';

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
  final TextEditingController _emailController = TextEditingController();
  final TextEditingController _professionController = TextEditingController();
  final TextEditingController _hobbiesController = TextEditingController();
  final TextEditingController _citationController = TextEditingController();
  final TextEditingController _loveChoirController = TextEditingController();
  final TextEditingController _dateNaissanceController = TextEditingController();
  String? _photoUrl;
  final ImagePicker _picker = ImagePicker();

  @override
  void initState() {
    super.initState();
    _loadProfile();
  }

  Future<void> _loadProfile() async {
    final user = Supabase.instance.client.auth.currentUser;
    if (user != null) {
      // Fallback initial values from Auth
      final metadata = user.userMetadata ?? {};
      if (mounted) {
        setState(() {
          _firstNameController.text = metadata['first_name'] ?? '';
          _lastNameController.text = metadata['last_name'] ?? '';
          _emailController.text = user.email ?? '';
        });
      }
    }

    _profileFuture = _profileService.fetchProfile();
    final profile = await _profileFuture;
    
    if (profile != null && mounted) {
      setState(() {
        _firstNameController.text = profile['first_name'] ?? _firstNameController.text;
        _lastNameController.text = profile['last_name'] ?? _lastNameController.text;
        _emailController.text = profile['email'] ?? _emailController.text;
        _professionController.text = profile['activite'] ?? '';
        _hobbiesController.text = profile['hobbie'] ?? '';
        _citationController.text = profile['citation'] ?? '';
        _loveChoirController.text = profile['love_choir'] ?? '';
        _photoUrl = profile['photo_url'];
        
        if (profile['date_naissance'] != null) {
          final date = DateTime.parse(profile['date_naissance']);
          _dateNaissanceController.text = DateFormat('yyyy-MM-dd').format(date);
        }

        // Pupitre info
        if (profile['pupitres'] != null) {
          _pupitreName = profile['pupitres']['name'];
        }
      });
    }
  }

  String? _pupitreName;

  @override
  void dispose() {
    _firstNameController.dispose();
    _lastNameController.dispose();
    _emailController.dispose();
    _professionController.dispose();
    _hobbiesController.dispose();
    _citationController.dispose();
    _loveChoirController.dispose();
    _dateNaissanceController.dispose();
    super.dispose();
  }

  Future<void> _saveProfile() async {
    setState(() => _isSaving = true);
    try {
      await _profileService.updateProfile({
        'first_name': _firstNameController.text,
        'last_name': _lastNameController.text,
        'email': _emailController.text,
        'activite': _professionController.text,
        'hobbie': _hobbiesController.text,
        'citation': _citationController.text,
        'love_choir': _loveChoirController.text,
        'date_naissance': _dateNaissanceController.text.isNotEmpty ? _dateNaissanceController.text : null,
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

  Future<void> _pickImage() async {
    final XFile? image = await _picker.pickImage(source: ImageSource.gallery, imageQuality: 70);
    if (image != null) {
      setState(() => _isSaving = true);
      try {
        final String? newPhotoUrl = await _profileService.uploadProfilePhoto(File(image.path));
        if (newPhotoUrl != null) {
          await _profileService.updateProfile({'photo_url': newPhotoUrl});
          setState(() {
            _photoUrl = newPhotoUrl;
          });
          if (mounted) {
            ScaffoldMessenger.of(context).showSnackBar(
              const SnackBar(content: Text("Photo de profil mise à jour"), backgroundColor: Colors.green),
            );
          }
        }
      } catch (e) {
        if (mounted) {
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(content: Text("Erreur upload: $e"), backgroundColor: Colors.red),
          );
        }
      } finally {
        if (mounted) setState(() => _isSaving = false);
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      appBar: AppBar(
        title: Text("Mon Profil", style: GoogleFonts.outfit(fontWeight: FontWeight.bold)),
        backgroundColor: Colors.white,
        foregroundColor: const Color(0xFF444050),
        elevation: 0,
        centerTitle: true,
        actions: [
          if (!_isEditing)
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

          return SingleChildScrollView(
            padding: const EdgeInsets.symmetric(horizontal: 25, vertical: 20),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                _buildHeaderCard(),
                const SizedBox(height: 35),
                if (_isEditing) ...[
                  _buildSectionTitle("Informations de base"),
                  _buildEditField("Prénom", _firstNameController, Icons.person_outline_rounded),
                  _buildEditField("Nom", _lastNameController, Icons.person_outline_rounded),
                  _buildEditField("Email", _emailController, Icons.email_outlined, enabled: false),
                  _buildDateField("Date de naissance", _dateNaissanceController, Icons.calendar_today_rounded),
                  const SizedBox(height: 25),
                  _buildSectionTitle("Vie & Activités"),
                  _buildEditField("Profession / Activité", _professionController, Icons.work_outline_rounded),
                  _buildEditField("Loisirs / Hobbies", _hobbiesController, Icons.palette_outlined),
                  _buildEditField("Citation personelle", _citationController, Icons.format_quote_rounded, maxLines: 2),
                  const SizedBox(height: 10),
                  _buildSectionTitle("Cœur de choriste"),
                  _buildEditField("Ce que j'aime dans la chorale", _loveChoirController, Icons.favorite_border_rounded, maxLines: 4),
                  const SizedBox(height: 30),
                  _buildSaveCancelButtons(),
                ] else ...[
                  _buildViewSection(),
                ],
                const SizedBox(height: 50),
              ],
            ),
          );
        },
      ),
    );
  }

  Widget _buildHeaderCard() {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(25),
      decoration: BoxDecoration(
        color: const Color(0xFFF8F7FA),
        borderRadius: BorderRadius.circular(30),
        border: Border.all(color: Colors.grey.shade100),
      ),
      child: Column(
        children: [
          GestureDetector(
            onTap: _isSaving ? null : _pickImage,
            child: Stack(
              children: [
                Container(
                  padding: const EdgeInsets.all(4),
                  decoration: BoxDecoration(
                    shape: BoxShape.circle,
                    border: Border.all(color: const Color(0xFF7367F0), width: 2),
                    boxShadow: [BoxShadow(color: const Color(0xFF7367F0).withAlpha(40), blurRadius: 15, offset: const Offset(0, 5))],
                  ),
                  child: CircleAvatar(
                    radius: 55,
                    backgroundColor: Colors.white,
                    backgroundImage: _photoUrl != null ? NetworkImage(_photoUrl!) : null,
                    child: _photoUrl == null 
                      ? const Icon(Icons.person_rounded, size: 60, color: Color(0xFF7367F0)) 
                      : (_isSaving ? const CircularProgressIndicator(color: Color(0xFF7367F0)) : null),
                  ),
                ),
                Positioned(
                  bottom: 5,
                  right: 5,
                  child: Container(
                    padding: const EdgeInsets.all(6),
                    decoration: const BoxDecoration(color: Color(0xFF7367F0), shape: BoxShape.circle),
                    child: const Icon(Icons.camera_alt_rounded, color: Colors.white, size: 16),
                  ),
                ),
              ],
            ),
          ),
          const SizedBox(height: 20),
          Text(
            "${_firstNameController.text} ${_lastNameController.text}",
            style: GoogleFonts.outfit(fontSize: 24, fontWeight: FontWeight.bold, color: const Color(0xFF444050)),
          ),
          Text(
            _emailController.text,
            style: GoogleFonts.outfit(color: Colors.grey[500], fontSize: 14),
          ),
          const SizedBox(height: 15),
          if (!_isEditing)
            ElevatedButton.icon(
              onPressed: () => setState(() => _isEditing = true),
              icon: const Icon(Icons.edit_rounded, size: 18),
              label: const Text("Modifier Profil"),
              style: ElevatedButton.styleFrom(
                backgroundColor: const Color(0xFF7367F0),
                foregroundColor: Colors.white,
                elevation: 0,
                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 12),
              ),
            ),
        ],
      ),
    );
  }

  Widget _buildViewSection() {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        if (_citationController.text.isNotEmpty) _buildCitationCard(_citationController.text),
        _buildSectionTitle("Vie & Activités"),
        _buildProfileItem(Icons.calendar_today_rounded, "Date de naissance", _dateNaissanceController.text.isNotEmpty ? DateFormat('dd/MM/yyyy').format(DateTime.parse(_dateNaissanceController.text)) : "Non renseignée"),
        _buildProfileItem(Icons.work_outline_rounded, "Profession", _professionController.text.isNotEmpty ? _professionController.text : "Non renseignée"),
        _buildProfileItem(Icons.palette_outlined, "Loisirs", _hobbiesController.text.isNotEmpty ? _hobbiesController.text : "Non renseignés"),
        _buildProfileItem(Icons.mic_external_on_rounded, "Pupitre", _pupitreName ?? "Non renseigné"),
        
        if (_loveChoirController.text.isNotEmpty) ...[
          const SizedBox(height: 15),
          _buildSectionTitle("Cœur de choriste"),
          _buildInfoCard("Ce que j'aime dans la chorale", _loveChoirController.text, const Color(0xFFEA5455)),
        ],
      ],
    );
  }

  Widget _buildSaveCancelButtons() {
    return Row(
      children: [
        Expanded(
          child: OutlinedButton(
            onPressed: () {
              setState(() => _isEditing = false);
              _loadProfile();
            },
            style: OutlinedButton.styleFrom(
              padding: const EdgeInsets.symmetric(vertical: 16),
              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(15)),
              side: BorderSide(color: Colors.grey.shade300),
            ),
            child: Text("Annuler", style: GoogleFonts.outfit(fontWeight: FontWeight.bold, color: const Color(0xFF444050))),
          ),
        ),
        const SizedBox(width: 15),
        Expanded(
          flex: 2,
          child: ElevatedButton(
            onPressed: _isSaving ? null : _saveProfile,
            style: ElevatedButton.styleFrom(
              backgroundColor: const Color(0xFF7367F0),
              foregroundColor: Colors.white,
              padding: const EdgeInsets.symmetric(vertical: 16),
              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(15)),
              elevation: 0,
            ),
            child: _isSaving 
              ? const SizedBox(width: 20, height: 20, child: CircularProgressIndicator(color: Colors.white, strokeWidth: 2))
              : Text("Enregistrer les modifications", style: GoogleFonts.outfit(fontWeight: FontWeight.bold)),
          ),
        ),
      ],
    );
  }

  Widget _buildSectionTitle(String title) {
    return Padding(
      padding: const EdgeInsets.only(left: 5, bottom: 15, top: 10),
      child: Text(
        title.toUpperCase(),
        style: GoogleFonts.outfit(fontSize: 11, fontWeight: FontWeight.bold, color: Colors.grey[400], letterSpacing: 2),
      ),
    );
  }

  Widget _buildCitationCard(String citation) {
    return Container(
      margin: const EdgeInsets.only(bottom: 30),
      padding: const EdgeInsets.all(25),
      decoration: BoxDecoration(
        color: const Color(0xFF7367F0).withAlpha(10),
        borderRadius: BorderRadius.circular(25),
        border: Border.all(color: const Color(0xFF7367F0).withAlpha(20)),
      ),
      child: Column(
        children: [
          const Icon(Icons.format_quote_rounded, color: Color(0xFF7367F0), size: 35),
          const SizedBox(height: 10),
          Text(
            citation,
            textAlign: TextAlign.center,
            style: GoogleFonts.outfit(fontSize: 16, fontStyle: FontStyle.italic, color: const Color(0xFF444050), height: 1.6),
          ),
        ],
      ),
    );
  }

  Widget _buildInfoCard(String title, String content, Color accentColor) {
    return Container(
      margin: const EdgeInsets.only(bottom: 15),
      padding: const EdgeInsets.all(25),
      width: double.infinity,
      decoration: BoxDecoration(
        color: accentColor.withAlpha(10),
        borderRadius: BorderRadius.circular(25),
        border: Border.all(color: accentColor.withAlpha(20)),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(title, style: GoogleFonts.outfit(fontWeight: FontWeight.bold, color: accentColor, fontSize: 13)),
          const SizedBox(height: 12),
          Text(content, style: GoogleFonts.outfit(color: const Color(0xFF444050), fontSize: 15, height: 1.7)),
        ],
      ),
    );
  }

  Widget _buildProfileItem(IconData icon, String title, String value) {
    return Container(
      margin: const EdgeInsets.only(bottom: 15),
      padding: const EdgeInsets.all(18),
      decoration: BoxDecoration(
        color: const Color(0xFFF8F7FA),
        borderRadius: BorderRadius.circular(20),
      ),
      child: Row(
        children: [
          Container(
            padding: const EdgeInsets.all(10),
            decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(12)),
            child: Icon(icon, color: const Color(0xFF7367F0), size: 20),
          ),
          const SizedBox(width: 18),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(title, style: GoogleFonts.outfit(color: Colors.grey[500], fontSize: 11, fontWeight: FontWeight.bold)),
                const SizedBox(height: 2),
                Text(value, style: GoogleFonts.outfit(fontWeight: FontWeight.bold, color: const Color(0xFF444050), fontSize: 15)),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildEditField(String label, TextEditingController controller, IconData icon, {int maxLines = 1, bool enabled = true}) {
    return Container(
      margin: const EdgeInsets.only(bottom: 18),
      child: TextFormField(
        controller: controller,
        enabled: enabled,
        maxLines: maxLines,
        style: GoogleFonts.outfit(fontSize: 15),
        decoration: InputDecoration(
          labelText: label,
          labelStyle: GoogleFonts.outfit(color: Colors.grey[500], fontSize: 14),
          prefixIcon: Icon(icon, color: const Color(0xFF7367F0), size: 22),
          filled: true,
          fillColor: enabled ? const Color(0xFFF8F7FA) : Colors.grey.shade100,
          border: OutlineInputBorder(borderRadius: BorderRadius.circular(18), borderSide: BorderSide.none),
          contentPadding: const EdgeInsets.symmetric(horizontal: 20, vertical: 18),
        ),
      ),
    );
  }

  Widget _buildDateField(String label, TextEditingController controller, IconData icon) {
    return Container(
      margin: const EdgeInsets.only(bottom: 18),
      child: TextFormField(
        controller: controller,
        readOnly: true,
        onTap: () async {
          DateTime? pickedDate = await showDatePicker(
            context: context,
            initialDate: controller.text.isNotEmpty ? DateTime.parse(controller.text) : DateTime.now(),
            firstDate: DateTime(1900),
            lastDate: DateTime.now(),
          );
          if (pickedDate != null) {
            String formattedDate = DateFormat('yyyy-MM-dd').format(pickedDate);
            setState(() {
              controller.text = formattedDate;
            });
          }
        },
        style: GoogleFonts.outfit(fontSize: 15),
        decoration: InputDecoration(
          labelText: label,
          labelStyle: GoogleFonts.outfit(color: Colors.grey[500], fontSize: 14),
          prefixIcon: Icon(icon, color: const Color(0xFF7367F0), size: 22),
          filled: true,
          fillColor: const Color(0xFFF8F7FA),
          border: OutlineInputBorder(borderRadius: BorderRadius.circular(18), borderSide: BorderSide.none),
          contentPadding: const EdgeInsets.symmetric(horizontal: 20, vertical: 18),
        ),
      ),
    );
  }
}
