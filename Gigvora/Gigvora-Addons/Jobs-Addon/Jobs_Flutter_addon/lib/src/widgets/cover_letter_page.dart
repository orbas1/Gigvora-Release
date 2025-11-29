import 'package:flutter/material.dart';

import '../data/profile_repository.dart';
import '../models/models.dart';

class CoverLetterPage extends StatefulWidget {
  const CoverLetterPage({
    super.key,
    required this.profileRepository,
    required this.candidateId,
  });

  final ProfileRepository profileRepository;
  final int candidateId;

  @override
  State<CoverLetterPage> createState() => _CoverLetterPageState();
}

class _CoverLetterPageState extends State<CoverLetterPage> {
  final _controller = TextEditingController();
  bool _saving = false;
  String? _message;

  @override
  void dispose() {
    _controller.dispose();
    super.dispose();
  }

  Future<void> _save() async {
    setState(() {
      _saving = true;
      _message = null;
    });
    try {
      final saved = await widget.profileRepository.saveCoverLetter(
        candidateId: widget.candidateId,
        coverLetter: CoverLetter(
          candidateId: widget.candidateId,
          title: 'Cover Letter',
          body: _controller.text,
        ),
      );
      setState(() => _message = 'Saved cover letter ${saved.id}');
    } catch (e) {
      setState(() => _message = e.toString());
    } finally {
      setState(() => _saving = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Cover letter')),
      body: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          children: [
            TextField(
              controller: _controller,
              maxLines: 10,
              decoration: const InputDecoration(labelText: 'Cover letter'),
            ),
            const SizedBox(height: 12),
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
                      width: 16,
                      height: 16,
                      child: CircularProgressIndicator(strokeWidth: 2),
                    )
                  : const Text('Save'),
            ),
          ],
        ),
      ),
    );
  }
}
