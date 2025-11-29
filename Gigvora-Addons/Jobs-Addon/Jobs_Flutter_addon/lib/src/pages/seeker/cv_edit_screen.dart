import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../../models/cv.dart';
import '../../state/cv_state.dart';

class CvEditScreen extends StatefulWidget {
  const CvEditScreen({super.key, this.cv});

  final CvDocument? cv;

  @override
  State<CvEditScreen> createState() => _CvEditScreenState();
}

class _CvEditScreenState extends State<CvEditScreen> {
  late TextEditingController _name;
  late TextEditingController _summary;
  late TextEditingController _skills;

  @override
  void initState() {
    super.initState();
    _name = TextEditingController(text: widget.cv?.name ?? '');
    _summary = TextEditingController(text: widget.cv?.summary ?? '');
    _skills = TextEditingController(text: widget.cv?.skills.join(', ') ?? '');
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Edit CV')),
      floatingActionButton: FloatingActionButton(
        onPressed: () => _save(context),
        child: const Icon(Icons.save),
      ),
      body: Padding(
        padding: const EdgeInsets.all(16),
        child: ListView(
          children: [
            TextField(controller: _name, decoration: const InputDecoration(labelText: 'Name')),
            TextField(controller: _summary, decoration: const InputDecoration(labelText: 'Summary'), maxLines: 3),
            TextField(controller: _skills, decoration: const InputDecoration(labelText: 'Skills (comma separated)')),
            const SizedBox(height: 16),
            ElevatedButton(
              onPressed: () => _save(context),
              child: const Text('Save'),
            )
          ],
        ),
      ),
    );
  }

  void _save(BuildContext context) {
    final state = context.read<CvState>();
    final updated = CvDocument(
      id: widget.cv?.id ?? 0,
      name: _name.text,
      summary: _summary.text,
      skills: _skills.text.split(',').map((e) => e.trim()).where((e) => e.isNotEmpty).toList(),
      experience: widget.cv?.experience ?? const [],
      education: widget.cv?.education ?? const [],
    );
    state.save(updated);
    Navigator.pop(context);
  }
}
