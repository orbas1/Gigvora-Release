import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../../../state/core_providers.dart';
import '../../../state/project_provider.dart';
import '../../widgets/project_card.dart';
import 'project_detail_page.dart';
import '../../theme/mobile_theme.dart';

class ProjectListPage extends ConsumerWidget {
  const ProjectListPage({super.key});

  static const routeName = '/freelance/projects';

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final projectsState = ref.watch(projectsProvider);

    return Scaffold(
      appBar: AppBar(
        title: const Text('Projects'),
        actions: [
          IconButton(
            icon: const Icon(Icons.filter_alt_outlined),
            onPressed: () => _showFilters(context, ref),
          ),
        ],
      ),
      body: projectsState.when(
        data: (paged) => RefreshIndicator(
          onRefresh: () => ref.read(projectsProvider.notifier).refresh(),
          child: paged.items.isEmpty
              ? const _EmptyProjectState()
              : ListView.separated(
                  padding: FreelanceMobileTheme.screenPadding,
                  itemBuilder: (context, index) => ProjectCard(
                    project: paged.items[index],
                    onTap: () => _openProject(context, paged.items[index].slug),
                    onFavourite: () async => _toggleFavourite(context, ref, paged.items[index].id),
                  ),
                  separatorBuilder: (_, __) => const SizedBox(height: 8),
                  itemCount: paged.items.length,
                ),
        ),
        error: (error, _) => Center(child: Text('Failed to load projects: $error')),
        loading: () => const Center(child: CircularProgressIndicator()),
      ),
    );
  }

  void _openProject(BuildContext context, String slug) {
    Navigator.of(context).push(
      MaterialPageRoute(builder: (_) => ProjectDetailPage(slug: slug)),
    );
  }

  Future<void> _toggleFavourite(BuildContext context, WidgetRef ref, int id) async {
    try {
      await ref.read(freelanceRepositoryProvider).toggleFavourite(id: id, type: 'project');
      await ref.read(projectsProvider.notifier).refresh();
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Updated favourite status')),
      );
    } catch (error) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Unable to update favourite: $error')),
      );
    }
  }

  Future<void> _showFilters(BuildContext context, WidgetRef ref) async {
    final filters = ref.read(projectFiltersProvider);
    final result = await showDialog<ProjectFilters>(
      context: context,
      builder: (_) => _ProjectFilterDialog(initialFilters: filters),
    );
    if (result != null) {
      await ref.read(projectsProvider.notifier).updateFilters(result);
    }
  }
}

class _EmptyProjectState extends StatelessWidget {
  const _EmptyProjectState();

  @override
  Widget build(BuildContext context) {
    return Center(
      child: Padding(
        padding: const EdgeInsets.all(24),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            Icon(Icons.cases_outlined,
                size: 56, color: FreelanceMobileTheme.emphasisIcon(context)),
            const SizedBox(height: 12),
            const Text('No projects available right now.'),
          ],
        ),
      ),
    );
  }
}

class _ProjectFilterDialog extends StatefulWidget {
  const _ProjectFilterDialog({required this.initialFilters});

  final ProjectFilters initialFilters;

  @override
  State<_ProjectFilterDialog> createState() => _ProjectFilterDialogState();
}

class _ProjectFilterDialogState extends State<_ProjectFilterDialog> {
  late TextEditingController keywordController;
  late TextEditingController locationController;
  late TextEditingController minController;
  late TextEditingController maxController;

  @override
  void initState() {
    super.initState();
    keywordController = TextEditingController(text: widget.initialFilters.keyword);
    locationController = TextEditingController(text: widget.initialFilters.location ?? '');
    minController = TextEditingController(text: widget.initialFilters.minBudget?.toString() ?? '');
    maxController = TextEditingController(text: widget.initialFilters.maxBudget?.toString() ?? '');
  }

  @override
  Widget build(BuildContext context) {
    return AlertDialog(
      title: const Text('Project filters'),
      content: SingleChildScrollView(
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            TextField(
              controller: keywordController,
              decoration: const InputDecoration(labelText: 'Keyword'),
            ),
            TextField(
              controller: locationController,
              decoration: const InputDecoration(labelText: 'Location'),
            ),
            Row(
              children: [
                Expanded(
                  child: TextField(
                    controller: minController,
                    keyboardType: TextInputType.number,
                    decoration: const InputDecoration(labelText: 'Min budget'),
                  ),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: TextField(
                    controller: maxController,
                    keyboardType: TextInputType.number,
                    decoration: const InputDecoration(labelText: 'Max budget'),
                  ),
                ),
              ],
            ),
          ],
        ),
      ),
      actions: [
        TextButton(
          onPressed: () => Navigator.of(context).pop(widget.initialFilters),
          child: const Text('Reset'),
        ),
        ElevatedButton(
          onPressed: () {
            Navigator.of(context).pop(
              ProjectFilters(
                keyword: keywordController.text,
                location: locationController.text,
                minBudget: double.tryParse(minController.text),
                maxBudget: double.tryParse(maxController.text),
              ),
            );
          },
          child: const Text('Apply'),
        ),
      ],
    );
  }
}
