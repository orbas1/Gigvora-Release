import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../../config.dart';
import '../../services/api_client.dart';
import '../../services/jobs_service.dart';
import '../../state/jobs_state.dart';
import '../../widgets/job_card.dart';

class SavedJobsScreen extends StatelessWidget {
  const SavedJobsScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return ChangeNotifierProvider(
      create: (_) => JobsState(JobsService(JobsApiClient(baseUrl: JobsAddonConfig.baseUrl, defaultHeaders: JobsAddonConfig.defaultHeaders())))..loadSaved(),
      child: Scaffold(
        appBar: AppBar(title: const Text('Saved Jobs')),
        body: Consumer<JobsState>(
          builder: (context, state, _) => ListView.builder(
            padding: const EdgeInsets.all(12),
            itemCount: state.saved.length,
            itemBuilder: (context, index) {
              final job = state.saved[index];
              return JobCard(
                job: job,
                onTap: () => Navigator.pushNamed(context, '/jobs/detail/${job.id}', arguments: job),
                onSave: () => state.toggleSave(job),
                trailing: TextButton(
                  onPressed: () => Navigator.pushNamed(context, '/jobs/apply/${job.id}', arguments: job),
                  child: const Text('Apply'),
                ),
              );
            },
          ),
        ),
      ),
    );
  }
}
