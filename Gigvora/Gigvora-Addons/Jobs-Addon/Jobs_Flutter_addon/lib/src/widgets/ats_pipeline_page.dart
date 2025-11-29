import 'package:flutter/material.dart';

import '../data/jobs_repository.dart';
import '../models/models.dart';
import '../state/application_notifier.dart';

class AtsPipelinePage extends StatefulWidget {
  const AtsPipelinePage({
    super.key,
    required this.repository,
    required this.jobId,
  });

  final JobsRepository repository;
  final int jobId;

  @override
  State<AtsPipelinePage> createState() => _AtsPipelinePageState();
}

class _AtsPipelinePageState extends State<AtsPipelinePage> {
  late final ApplicationNotifier _notifier =
      ApplicationNotifier(widget.repository)..addListener(_refresh);

  @override
  void initState() {
    super.initState();
    _notifier.fetchPipeline(widget.jobId);
    _notifier.loadApplications();
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
    final pipeline = _notifier.pipeline;
    return Scaffold(
      appBar: AppBar(title: const Text('ATS pipeline')),
      body: pipeline == null
          ? const Center(child: CircularProgressIndicator())
          : ListView(
              padding: const EdgeInsets.all(16),
              children: pipeline.stages
                  .map(
                    (stage) => Card(
                      child: ExpansionTile(
                        title: Text(stage.name),
                        children: _notifier.applications
                            .where((app) => app.status.id == stage.id)
                            .map((app) => ListTile(
                                  title: Text(app.candidate.name),
                                  subtitle: Text(app.job.title),
                                  trailing: IconButton(
                                    icon: const Icon(Icons.chevron_right),
                                    onPressed: () => _advance(app, stage, pipeline),
                                  ),
                                ))
                            .toList(),
                      ),
                    ),
                  )
                  .toList(),
            ),
    );
  }

  Future<void> _advance(
    JobApplication app,
    AtsStage currentStage,
    AtsPipeline pipeline,
  ) async {
    final index = pipeline.stages.indexWhere((s) => s.id == currentStage.id);
    if (index + 1 >= pipeline.stages.length) return;
    final nextStage = pipeline.stages[index + 1];
    await _notifier.advanceStage(applicationId: app.id, stageId: nextStage.id);
  }
}
