import 'package:flutter/material.dart';

import '../data/jobs_repository.dart';
import '../models/models.dart';
import '../state/jobs_notifier.dart';
import 'job_detail_screen.dart';

class JobListScreen extends StatefulWidget {
  const JobListScreen({super.key, required this.repository});

  final JobsRepository repository;

  @override
  State<JobListScreen> createState() => _JobListScreenState();
}

class _JobListScreenState extends State<JobListScreen> {
  late final JobsNotifier _notifier = JobsNotifier(widget.repository)
    ..addListener(_refresh);
  final TextEditingController _searchController = TextEditingController();

  @override
  void initState() {
    super.initState();
    _notifier.loadJobs();
  }

  @override
  void dispose() {
    _notifier.removeListener(_refresh);
    _notifier.dispose();
    _searchController.dispose();
    super.dispose();
  }

  void _refresh() => setState(() {});

  Future<void> _submitSearch() async {
    await _notifier.loadJobs(search: _searchController.text.trim());
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Jobs')),
      body: Column(
        children: [
          Padding(
            padding: const EdgeInsets.all(12),
            child: Row(
              children: [
                Expanded(
                  child: TextField(
                    controller: _searchController,
                    decoration: const InputDecoration(
                      labelText: 'Search roles, companies, or locations',
                    ),
                    onSubmitted: (_) => _submitSearch(),
                  ),
                ),
                IconButton(
                  icon: const Icon(Icons.search),
                  onPressed: _submitSearch,
                ),
              ],
            ),
          ),
          if (_notifier.loading)
            const LinearProgressIndicator(minHeight: 2),
          if (_notifier.error != null)
            Padding(
              padding: const EdgeInsets.all(12),
              child: Text(
                _notifier.error!,
                style: const TextStyle(color: Colors.red),
              ),
            ),
          Expanded(
            child: RefreshIndicator(
              onRefresh: () => _notifier.loadJobs(search: _searchController.text),
              child: ListView.builder(
                itemCount: _notifier.jobs.length,
                itemBuilder: (context, index) {
                  final Job job = _notifier.jobs[index];
                  return ListTile(
                    title: Text(job.title),
                    subtitle: Text('${job.company.name} — ${job.location}'),
                    trailing: Text(job.type.toUpperCase()),
                    onTap: () async {
                      await Navigator.of(context).push(
                        MaterialPageRoute(
                          builder: (_) => JobDetailScreen(
                            repository: widget.repository,
                            jobId: job.id,
                          ),
                        ),
                      );
                    },
                  );
                },
              ),
            ),
          ),
        ],
      ),
    );
  }
}
