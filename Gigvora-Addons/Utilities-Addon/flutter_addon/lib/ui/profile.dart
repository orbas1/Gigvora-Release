import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../analytics/analytics_client.dart';
import '../models/models.dart';
import '../state/state.dart';
import 'common.dart';

class ProfessionalProfileScreen extends StatefulWidget {
  final ProfessionalProfileState? state;
  final AnalyticsClient analytics;
  final int? userId;

  const ProfessionalProfileScreen({
    super.key,
    required this.analytics,
    this.state,
    this.userId,
  });

  @override
  State<ProfessionalProfileScreen> createState() => _ProfessionalProfileScreenState();
}

class _ProfessionalProfileScreenState extends State<ProfessionalProfileScreen> {
  ProfessionalProfileState get state => widget.state ?? context.read<ProfessionalProfileState>();

  @override
  void initState() {
    super.initState();
    widget.analytics.trackScreen('ProfessionalProfile', {'userId': widget.userId});
    state.load(userId: widget.userId);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Professional Profile'),
        actions: [
          IconButton(
            icon: const Icon(Icons.edit),
            onPressed: () => Navigator.of(context).pushNamed('/professional-profile/edit'),
          ),
        ],
      ),
      body: Consumer<ProfessionalProfileState>(
        builder: (context, s, _) {
          if (s.loading && s.profile == null) {
            return const LoadingView();
          }
          if (s.error != null) {
            return ErrorView(message: s.error!, onRetry: () => state.load(userId: widget.userId));
          }
          final profile = s.profile;
          if (profile == null) {
            return const EmptyView(message: 'Profile not found');
          }
          return RefreshIndicator(
            onRefresh: () => state.load(userId: widget.userId),
            child: ListView(
              padding: const EdgeInsets.all(16),
              children: [
                ListTile(
                  leading: const CircleAvatar(child: Icon(Icons.person)),
                  title: Text(profile.name),
                  subtitle: Text(profile.tagline ?? ''),
                  trailing: profile.availableForWork ? const Chip(label: Text('Available')) : null,
                ),
                const SizedBox(height: 12),
                _ChipSection(label: 'Skills', values: profile.skills),
                _ChipSection(label: 'Top Skills', values: profile.topSkills),
                _ChipSection(label: 'Interests', values: profile.interests),
                _ListSection(title: 'Experience', items: profile.experience),
                _ListSection(title: 'Education', items: profile.education),
                _ListSection(title: 'Certifications', items: profile.certifications),
                _ListSection(title: 'References', items: profile.references),
                ListTile(
                  title: const Text('DBS Cleared'),
                  trailing: Icon(profile.dbsCleared ? Icons.check_circle : Icons.remove_circle_outline,
                      color: profile.dbsCleared ? Colors.green : Colors.grey),
                ),
              ],
            ),
          );
        },
      ),
    );
  }
}

class EditProfessionalProfileScreen extends StatefulWidget {
  final ProfessionalProfileState? state;
  final AnalyticsClient analytics;

  const EditProfessionalProfileScreen({super.key, required this.analytics, this.state});

  @override
  State<EditProfessionalProfileScreen> createState() => _EditProfessionalProfileScreenState();
}

class _EditProfessionalProfileScreenState extends State<EditProfessionalProfileScreen> {
  final _formKey = GlobalKey<FormState>();
  late TextEditingController _tagline;
  late TextEditingController _location;
  late TextEditingController _skills;
  late TextEditingController _interests;
  ProfessionalProfileState get state => widget.state ?? context.read<ProfessionalProfileState>();

  @override
  void initState() {
    super.initState();
    widget.analytics.trackScreen('EditProfessionalProfile', {});
    final profile = state.profile;
    _tagline = TextEditingController(text: profile?.tagline ?? '');
    _location = TextEditingController(text: profile?.location ?? '');
    _skills = TextEditingController(text: profile?.skills.join(', ') ?? '');
    _interests = TextEditingController(text: profile?.interests.join(', ') ?? '');
  }

  @override
  void dispose() {
    _tagline.dispose();
    _location.dispose();
    _skills.dispose();
    _interests.dispose();
    super.dispose();
  }

  Future<void> _save() async {
    if (!_formKey.currentState!.validate()) return;
    final payload = {
      'tagline': _tagline.text.trim(),
      'location': _location.text.trim(),
      'skills': _skills.text.split(',').map((e) => e.trim()).where((e) => e.isNotEmpty).toList(),
      'interests': _interests.text.split(',').map((e) => e.trim()).where((e) => e.isNotEmpty).toList(),
    };
    await state.update(payload);
    widget.analytics.trackAction('profile_updated', payload);
    if (mounted) Navigator.of(context).pop();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Edit Profile')),
      body: Padding(
        padding: const EdgeInsets.all(16),
        child: Form(
          key: _formKey,
          child: ListView(
            children: [
              TextFormField(
                controller: _tagline,
                decoration: const InputDecoration(labelText: 'Tagline'),
                validator: (v) => v != null && v.length > 120 ? 'Too long' : null,
              ),
              TextFormField(
                controller: _location,
                decoration: const InputDecoration(labelText: 'Location'),
              ),
              TextFormField(
                controller: _skills,
                decoration: const InputDecoration(labelText: 'Skills (comma separated)'),
              ),
              TextFormField(
                controller: _interests,
                decoration: const InputDecoration(labelText: 'Interests (comma separated)'),
              ),
              const SizedBox(height: 16),
              Consumer<ProfessionalProfileState>(
                builder: (context, s, _) {
                  if (s.loading) {
                    return const LoadingView(message: 'Saving...');
                  }
                  if (s.error != null) {
                    return ErrorView(message: s.error!, onRetry: _save);
                  }
                  return ElevatedButton(onPressed: _save, child: const Text('Save'));
                },
              ),
            ],
          ),
        ),
      ),
    );
  }
}

class _ChipSection extends StatelessWidget {
  final String label;
  final List<String> values;

  const _ChipSection({required this.label, required this.values});

  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(label, style: Theme.of(context).textTheme.titleSmall),
        const SizedBox(height: 8),
        Wrap(
          spacing: 8,
          runSpacing: 8,
          children: values.isEmpty
              ? [const Text('None')]
              : values.map((v) => Chip(label: Text(v))).toList(),
        ),
        const SizedBox(height: 12),
      ],
    );
  }
}

class _ListSection extends StatelessWidget {
  final String title;
  final List<String> items;

  const _ListSection({required this.title, required this.items});

  @override
  Widget build(BuildContext context) {
    if (items.isEmpty) return const SizedBox.shrink();
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(title, style: Theme.of(context).textTheme.titleSmall),
        ...items.map((e) => ListTile(
              dense: true,
              title: Text(e),
            )),
        const SizedBox(height: 8),
      ],
    );
  }
}
