import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../../repositories/freelance_repository.dart';
import '../../ui/widgets/project_card.dart';

class MyProposalsScreen extends ConsumerWidget {
  const MyProposalsScreen({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final proposals = ref.watch(_proposalProjectsProvider);

    return Scaffold(
      appBar: AppBar(
        title: const Text('My Proposals'),
        actions: [
          IconButton(
            icon: const Icon(Icons.refresh),
            onPressed: () => ref.refresh(_proposalProjectsProvider),
          ),
        ],
      ),
      body: proposals.when(
        data: (projects) => projects.isEmpty
            ? const Center(child: Text('No proposals yet. Submit one from a project detail page.'))
            : ListView.separated(
                padding: const EdgeInsets.all(16),
                itemBuilder: (context, index) => ProjectCard(
                  project: projects[index],
                  onTap: () => Navigator.pushNamed(context, '/freelance/freelancer/project', arguments: projects[index]),
                ),
                separatorBuilder: (_, __) => const SizedBox(height: 8),
                itemCount: projects.length,
              ),
        error: (error, _) => Center(child: Text('Unable to load proposals: $error')),
        loading: () => const Center(child: CircularProgressIndicator()),
      ),
    );
  }
}

final _proposalProjectsProvider = FutureProvider.autoDispose((ref) async {
  final repository = ref.watch(freelanceRepositoryProvider);
  // Use recommendations as a proxy for recent proposal submissions until a dedicated endpoint exists.
  final recommendations = await repository.fetchRecommendations(limit: 10);
  return recommendations.projects;
});
