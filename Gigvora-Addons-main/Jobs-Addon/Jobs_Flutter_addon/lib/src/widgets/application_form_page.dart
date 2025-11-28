import 'package:flutter/material.dart';

import '../data/jobs_repository.dart';
import '../models/models.dart';

class ApplicationFormPage extends StatefulWidget {
  const ApplicationFormPage({super.key, required this.repository, required this.job});

  final JobsRepository repository;
  final Job job;

  @override
  State<ApplicationFormPage> createState() => _ApplicationFormPageState();
}

class _ApplicationFormPageState extends State<ApplicationFormPage> {
  final _formKey = GlobalKey<FormState>();
  final _nameController = TextEditingController();
  final _titleController = TextEditingController();
  final _locationController = TextEditingController();
  final _coverLetterController = TextEditingController();
  final Map<int, TextEditingController> _answers = {};

  bool _submitting = false;
  String? _result;

  @override
  void initState() {
    super.initState();
    for (final question in widget.job.screeningQuestions) {
      _answers[question.id] = TextEditingController();
    }
  }

  @override
  void dispose() {
    for (final controller in _answers.values) {
      controller.dispose();
    }
    _nameController.dispose();
    _titleController.dispose();
    _locationController.dispose();
    _coverLetterController.dispose();
    super.dispose();
  }

  Future<void> _submit() async {
    final valid = _formKey.currentState?.validate() ?? false;
    if (!valid) return;
    setState(() {
      _submitting = true;
      _result = null;
    });
    try {
      final candidate = CandidateProfile(
        id: 0,
        name: _nameController.text,
        title: _titleController.text,
        location: _locationController.text,
      );
      final answers = widget.job.screeningQuestions
          .map((q) => ScreeningAnswer(
                questionId: q.id,
                answer: _answers[q.id]?.text ?? '',
              ))
          .toList();
      final coverLetter = CoverLetter(id: 0, content: _coverLetterController.text);
      await widget.repository.apply(
        jobId: widget.job.id,
        candidate: candidate,
        answers: answers,
        coverLetter: coverLetter,
      );
      setState(() => _result = 'Application submitted');
    } catch (e) {
      setState(() => _result = e.toString());
    } finally {
      setState(() => _submitting = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('Apply to ${widget.job.title}')),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(16),
        child: Form(
          key: _formKey,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              TextFormField(
                controller: _nameController,
                decoration: const InputDecoration(labelText: 'Full name'),
                validator: (value) => value == null || value.isEmpty ? 'Required' : null,
              ),
              TextFormField(
                controller: _titleController,
                decoration: const InputDecoration(labelText: 'Headline / Title'),
              ),
              TextFormField(
                controller: _locationController,
                decoration: const InputDecoration(labelText: 'Location'),
              ),
              const SizedBox(height: 12),
              TextFormField(
                controller: _coverLetterController,
                decoration: const InputDecoration(labelText: 'Cover letter'),
                maxLines: 6,
              ),
              const SizedBox(height: 12),
              for (final question in widget.job.screeningQuestions)
                TextFormField(
                  controller: _answers[question.id],
                  decoration: InputDecoration(labelText: question.prompt),
                ),
              const SizedBox(height: 20),
              if (_result != null)
                Text(
                  _result!,
                  style: TextStyle(color: _result == 'Application submitted'
                      ? Colors.green
                      : Colors.red),
                ),
              const SizedBox(height: 12),
              FilledButton(
                onPressed: _submitting ? null : _submit,
                child: _submitting
                    ? const SizedBox(
                        height: 16,
                        width: 16,
                        child: CircularProgressIndicator(strokeWidth: 2),
                      )
                    : const Text('Submit application'),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
