import 'package:flutter/material.dart';

import '../data/profile_repository.dart';
import '../models/models.dart';

class CvBuilderPage extends StatefulWidget {
  const CvBuilderPage({
    super.key,
    required this.profileRepository,
    required this.candidateId,
  });

  final ProfileRepository profileRepository;
  final int candidateId;

  @override
  State<CvBuilderPage> createState() => _CvBuilderPageState();
}

class _CvBuilderPageState extends State<CvBuilderPage> {
  final _formKey = GlobalKey<FormState>();
  final _templateController = TextEditingController(text: 'modern');
  final _urlController = TextEditingController();
  bool _saving = false;
  String? _message;

  @override
  void dispose() {
    _templateController.dispose();
    _urlController.dispose();
    super.dispose();
  }

  Future<void> _save() async {
    final valid = _formKey.currentState?.validate() ?? false;
    if (!valid) return;
    setState(() {
      _saving = true;
      _message = null;
    });
    try {
      final saved = await widget.profileRepository.saveCv(
        candidateId: widget.candidateId,
        cv: CvDocument(
          candidateId: widget.candidateId,
          title: 'CV - ${_templateController.text}',
          content: {
            'template': _templateController.text,
            'url': _urlController.text,
          },
        ),
      );
      setState(() => _message = 'Saved CV ${saved.title}');
    } catch (e) {
      setState(() => _message = e.toString());
    } finally {
      setState(() => _saving = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('CV builder')),
      body: Padding(
        padding: const EdgeInsets.all(16),
        child: Form(
          key: _formKey,
          child: Column(
            children: [
              TextFormField(
                controller: _templateController,
                decoration: const InputDecoration(labelText: 'Template key'),
              ),
              TextFormField(
                controller: _urlController,
                decoration: const InputDecoration(labelText: 'Hosted CV URL'),
                validator: (value) => value == null || value.isEmpty ? 'Required' : null,
              ),
              const SizedBox(height: 16),
              if (_message != null)
                Align(
                  alignment: Alignment.centerLeft,
                  child: Text(
                    _message!,
                    style: TextStyle(
                      color: _message!.startsWith('Saved') ? Colors.green : Colors.red,
                    ),
                  ),
                ),
              const SizedBox(height: 12),
              FilledButton(
                onPressed: _saving ? null : _save,
                child: _saving
                    ? const SizedBox(
                        height: 16,
                        width: 16,
                        child: CircularProgressIndicator(strokeWidth: 2),
                      )
                    : const Text('Save CV'),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
