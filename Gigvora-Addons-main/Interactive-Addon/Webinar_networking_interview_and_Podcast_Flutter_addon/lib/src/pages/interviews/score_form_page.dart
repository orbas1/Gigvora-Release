import 'package:flutter/material.dart';

import '../../models/interview_score.dart';
import '../../models/interview_slot.dart';

class ScoreFormPage extends StatefulWidget {
  final InterviewSlot slot;

  const ScoreFormPage({super.key, required this.slot});

  @override
  State<ScoreFormPage> createState() => _ScoreFormPageState();
}

class _ScoreFormPageState extends State<ScoreFormPage> {
  final _formKey = GlobalKey<FormState>();
  final _criteriaController = TextEditingController();
  final _scoresController = TextEditingController();
  final _commentsController = TextEditingController();

  @override
  void dispose() {
    _criteriaController.dispose();
    _scoresController.dispose();
    _commentsController.dispose();
    super.dispose();
  }

  List<String> _splitInput(String input) =>
      input.split(',').map((part) => part.trim()).where((part) => part.isNotEmpty).toList();

  void _submit() {
    if (!_formKey.currentState!.validate()) return;
    final criteria = _splitInput(_criteriaController.text);
    final scores = _splitInput(_scoresController.text);
    Navigator.of(context).pop(
      InterviewScorePayload(criteria: criteria, scores: scores, comments: _commentsController.text.isEmpty ? null : _commentsController.text),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Submit Score')),
      body: Padding(
        padding: const EdgeInsets.all(16),
        child: Form(
          key: _formKey,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text('Scoring for Interviewee ' + widget.slot.intervieweeId.toString()),
              const SizedBox(height: 12),
              TextFormField(
                controller: _criteriaController,
                decoration: const InputDecoration(labelText: 'Criteria (comma separated)'),
                validator: (value) => (value == null || value.isEmpty) ? 'Enter at least one criterion' : null,
              ),
              TextFormField(
                controller: _scoresController,
                decoration: const InputDecoration(labelText: 'Scores (comma separated)'),
                validator: (value) => (value == null || value.isEmpty) ? 'Enter scores' : null,
              ),
              TextFormField(
                controller: _commentsController,
                decoration: const InputDecoration(labelText: 'Comments'),
                maxLines: 3,
              ),
              const SizedBox(height: 16),
              ElevatedButton(
                onPressed: _submit,
                child: const Text('Submit'),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
