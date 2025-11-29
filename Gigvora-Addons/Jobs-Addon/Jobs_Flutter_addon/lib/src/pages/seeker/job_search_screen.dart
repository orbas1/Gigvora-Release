import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../../config.dart';
import '../../services/api_client.dart';
import '../../services/jobs_service.dart';
import '../../state/jobs_state.dart';
import '../../widgets/job_card.dart';

class JobSearchScreen extends StatefulWidget {
  const JobSearchScreen({super.key});

  @override
  State<JobSearchScreen> createState() => _JobSearchScreenState();
}

class _JobSearchScreenState extends State<JobSearchScreen> {
  final _keywordController = TextEditingController();
  final _locationController = TextEditingController();

  @override
  Widget build(BuildContext context) {
    return ChangeNotifierProvider(
      create: (_) => JobsState(JobsService(JobsApiClient(baseUrl: JobsAddonConfig.baseUrl, defaultHeaders: JobsAddonConfig.defaultHeaders())))..loadJobs(),
      child: Scaffold(
        appBar: AppBar(title: const Text('Search Jobs'), actions: [
          IconButton(
            icon: const Icon(Icons.filter_list),
            onPressed: () => _openFilterSheet(context),
          )
        ]),
        body: Column(
          children: [
            Padding(
              padding: const EdgeInsets.all(12),
              child: Row(
                children: [
                  Expanded(
                    child: TextField(
                      controller: _keywordController,
                      decoration: const InputDecoration(hintText: 'Keyword or title'),
                    ),
                  ),
                  const SizedBox(width: 8),
                  Expanded(
                    child: TextField(
                      controller: _locationController,
                      decoration: const InputDecoration(hintText: 'Location'),
                    ),
                  ),
                  IconButton(
                    icon: const Icon(Icons.search),
                    onPressed: () => _performSearch(context),
                  )
                ],
              ),
            ),
            Expanded(
              child: Consumer<JobsState>(
                builder: (context, state, _) {
                  if (state.loading) {
                    return const Center(child: CircularProgressIndicator());
                  }
                  return RefreshIndicator(
                    onRefresh: () => state.loadJobs(keyword: _keywordController.text, location: _locationController.text),
                    child: ListView.builder(
                      padding: const EdgeInsets.symmetric(horizontal: 12),
                      itemCount: state.jobs.length,
                      itemBuilder: (context, index) {
                        final job = state.jobs[index];
                        return JobCard(
                          job: job,
                          onTap: () => Navigator.pushNamed(context, '/jobs/detail/${job.id}', arguments: job),
                          onSave: () => state.toggleSave(job),
                        );
                      },
                    ),
                  );
                },
              ),
            ),
          ],
        ),
      ),
    );
  }

  void _performSearch(BuildContext context) {
    final state = context.read<JobsState>();
    state.loadJobs(keyword: _keywordController.text, location: _locationController.text);
  }

  Future<void> _openFilterSheet(BuildContext context) async {
    await showModalBottomSheet(
      context: context,
      builder: (context) => Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            const Text('Filters', style: TextStyle(fontWeight: FontWeight.bold)),
            const SizedBox(height: 8),
            Wrap(
              spacing: 8,
              children: const [
                Chip(label: Text('Remote')),
                Chip(label: Text('Full-time')),
                Chip(label: Text('Contract')),
              ],
            ),
            const SizedBox(height: 12),
            ElevatedButton(
              onPressed: () {
                Navigator.pop(context);
                _performSearch(context);
              },
              child: const Text('Apply Filters'),
            )
          ],
        ),
      ),
    );
  }
}
