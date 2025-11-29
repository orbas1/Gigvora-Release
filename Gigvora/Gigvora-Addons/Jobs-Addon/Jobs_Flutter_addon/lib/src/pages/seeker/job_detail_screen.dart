import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../../config.dart';
import '../../models/job.dart';
import '../../services/api_client.dart';
import '../../services/jobs_service.dart';
import '../../state/jobs_state.dart';
import '../../widgets/job_card.dart';

class JobDetailScreen extends StatelessWidget {
  const JobDetailScreen({super.key, required this.jobId});

  final int jobId;

  @override
  Widget build(BuildContext context) {
    final service = JobsService(JobsApiClient(baseUrl: JobsAddonConfig.baseUrl, defaultHeaders: JobsAddonConfig.defaultHeaders()));
    return FutureBuilder<Job>(
      future: service.fetchJob(jobId),
      builder: (context, snapshot) {
        final job = snapshot.data;
        return Scaffold(
          appBar: AppBar(title: Text(job?.title ?? 'Job Detail')),
          bottomNavigationBar: job == null
              ? null
              : SafeArea(
                  child: Padding(
                    padding: const EdgeInsets.all(12),
                    child: Row(
                      children: [
                        IconButton(
                          icon: const Icon(Icons.bookmark_border),
                          onPressed: () => context.read<JobsState?>()?.toggleSave(job),
                        ),
                        const SizedBox(width: 8),
                        Expanded(
                          child: ElevatedButton(
                            onPressed: () => Navigator.pushNamed(context, '/jobs/apply/$jobId', arguments: job),
                            child: const Text('Apply Now'),
                          ),
                        ),
                      ],
                    ),
                  ),
                ),
          body: snapshot.connectionState == ConnectionState.waiting
              ? const Center(child: CircularProgressIndicator())
              : SingleChildScrollView(
                  padding: const EdgeInsets.all(16),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(job?.title ?? '', style: Theme.of(context).textTheme.headlineSmall),
                      Text(job?.company.name ?? ''),
                      const SizedBox(height: 12),
                      Text(job?.description ?? ''),
                      const SizedBox(height: 16),
                      Text('Requirements', style: Theme.of(context).textTheme.titleMedium),
                      const SizedBox(height: 8),
                      ...?job?.screeningQuestions.map((q) => ListTile(
                            dense: true,
                            leading: const Icon(Icons.help_outline),
                            title: Text(q.prompt),
                          )),
                      const SizedBox(height: 16),
                      Text('Similar jobs', style: Theme.of(context).textTheme.titleMedium),
                      FutureBuilder<List<Job>>(
                        future: job == null ? null : service.fetchSimilar(job),
                        builder: (context, similarSnapshot) {
                          final similar = similarSnapshot.data ?? [];
                          return Column(
                            children: similar
                                .map(
                                  (j) => JobCard(
                                    job: j,
                                    onTap: () => Navigator.pushReplacementNamed(context, '/jobs/detail/${j.id}', arguments: j),
                                  ),
                                )
                                .toList(),
                          );
                        },
                      )
                    ],
                  ),
                ),
        );
      },
    );
  }
}
