import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../../config.dart';
import '../../models/job.dart';
import '../../services/api_client.dart';
import '../../services/jobs_service.dart';
import '../../state/jobs_state.dart';
import '../../widgets/job_card.dart';

class JobsHomeScreen extends StatelessWidget {
  const JobsHomeScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return ChangeNotifierProvider(
      create: (_) => JobsState(
        JobsService(
          JobsApiClient(baseUrl: JobsAddonConfig.baseUrl, defaultHeaders: JobsAddonConfig.defaultHeaders()),
        ),
      )..loadJobs(),
      child: Scaffold(
        appBar: AppBar(title: const Text('Jobs')),
        body: Consumer<JobsState>(
          builder: (context, state, _) {
            return RefreshIndicator(
              onRefresh: () => state.loadJobs(),
              child: ListView(
                padding: const EdgeInsets.all(16),
                children: [
                  Text('Recommended for you', style: Theme.of(context).textTheme.titleMedium),
                  const SizedBox(height: 8),
                  SizedBox(
                    height: 200,
                    child: state.loading
                        ? const Center(child: CircularProgressIndicator())
                        : ListView.separated(
                            scrollDirection: Axis.horizontal,
                            itemCount: state.jobs.length,
                            separatorBuilder: (_, __) => const SizedBox(width: 12),
                            itemBuilder: (context, index) {
                              final job = state.jobs[index];
                              return SizedBox(
                                width: 260,
                                child: JobCard(
                                  job: job,
                                  onTap: () => Navigator.pushNamed(context, '/jobs/detail/${job.id}', arguments: job),
                                  onSave: () => state.toggleSave(job),
                                ),
                              );
                            },
                          ),
                  ),
                  const SizedBox(height: 16),
                  Text('Recently viewed', style: Theme.of(context).textTheme.titleMedium),
                  ...state.jobs.take(3).map(
                        (job) => JobCard(
                          job: job,
                          onTap: () => Navigator.pushNamed(context, '/jobs/detail/${job.id}', arguments: job),
                          onSave: () => state.toggleSave(job),
                        ),
                      ),
                  const SizedBox(height: 8),
                  ElevatedButton(
                    onPressed: () => Navigator.pushNamed(context, '/jobs/search'),
                    child: const Text('Browse all jobs'),
                  ),
                ],
              ),
            );
          },
        ),
      ),
    );
  }
}
