import 'package:flutter/material.dart';

import '../data/jobs_repository.dart';
import '../models/models.dart';
import '../state/jobs_notifier.dart';
import 'application_form_page.dart';

class JobDetailScreen extends StatefulWidget {
  const JobDetailScreen({super.key, required this.repository, required this.jobId});

  final JobsRepository repository;
  final int jobId;

  @override
  State<JobDetailScreen> createState() => _JobDetailScreenState();
}

class _JobDetailScreenState extends State<JobDetailScreen> {
  late final JobsNotifier _notifier = JobsNotifier(widget.repository)
    ..addListener(_refresh);

  @override
  void initState() {
    super.initState();
    _notifier.loadJob(widget.jobId);
  }

  @override
  void dispose() {
    _notifier.removeListener(_refresh);
    _notifier.dispose();
    super.dispose();
  }

  void _refresh() => setState(() {});

  @override
  Widget build(BuildContext context) {
    final job = _notifier.selected;
    return Scaffold(
      appBar: AppBar(title: const Text('Job details')),
      body: job == null
          ? const Center(child: CircularProgressIndicator())
          : SingleChildScrollView(
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(job.title, style: Theme.of(context).textTheme.headlineSmall),
                  const SizedBox(height: 8),
                  Text('${job.company.name} • ${job.location}'),
                  const SizedBox(height: 8),
                  Text(job.description),
                  const SizedBox(height: 12),
                  Wrap(
                    spacing: 8,
                    runSpacing: 8,
                    children: job.screeningQuestions
                        .map((q) => Chip(label: Text(q.prompt)))
                        .toList(),
                  ),
                  const SizedBox(height: 24),
                  FilledButton.icon(
                    icon: const Icon(Icons.send),
                    label: const Text('Apply'),
                    onPressed: () {
                      Navigator.of(context).push(
                        MaterialPageRoute(
                          builder: (_) => ApplicationFormPage(
                            repository: widget.repository,
                            job: job,
                          ),
                        ),
                      );
                    },
                  ),
                ],
              ),
            ),
    );
  }
}
