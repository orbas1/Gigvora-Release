import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../../../models/gig.dart';
import '../../../state/core_providers.dart';
import '../../../state/gig_provider.dart';
import '../../widgets/gig_card.dart';
import 'gig_detail_page.dart';
import 'gig_filter_sheet.dart';

class GigsListPage extends ConsumerWidget {
  const GigsListPage({super.key});

  static const routeName = '/freelance/gigs';

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final gigsState = ref.watch(gigsProvider);

    return Scaffold(
      appBar: AppBar(
        title: const Text('Gigs'),
        actions: [
          IconButton(
            icon: const Icon(Icons.filter_alt_outlined),
            onPressed: () => _showFilters(context, ref),
          ),
        ],
      ),
      body: gigsState.when(
        data: (paged) => RefreshIndicator(
          onRefresh: () => ref.read(gigsProvider.notifier).refresh(),
          child: paged.items.isEmpty
              ? const _EmptyState()
              : ListView.separated(
                  padding: const EdgeInsets.all(12),
                  itemBuilder: (context, index) => GigCard(
                    gig: paged.items[index],
                    onTap: () => _openGig(context, paged.items[index]),
                    onFavourite: () async => _toggleFavourite(context, ref, paged.items[index]),
                  ),
                  separatorBuilder: (_, __) => const SizedBox(height: 8),
                  itemCount: paged.items.length,
                ),
        ),
        error: (error, _) => Center(child: Text('Failed to load gigs: $error')),
        loading: () => const Center(child: CircularProgressIndicator()),
      ),
    );
  }

  void _openGig(BuildContext context, Gig gig) {
    Navigator.of(context).push(
      MaterialPageRoute(builder: (_) => GigDetailPage(gigId: gig.id)),
    );
  }

  Future<void> _toggleFavourite(BuildContext context, WidgetRef ref, Gig gig) async {
    final notifier = ref.read(gigsProvider.notifier);
    try {
      await ref.read(freelanceRepositoryProvider).toggleFavourite(id: gig.id, type: 'gig');
      await notifier.refresh();
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Updated favourite status')),
      );
    } catch (error) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Unable to update favourite: $error')),
      );
    }
  }

  void _showFilters(BuildContext context, WidgetRef ref) async {
    final filters = ref.read(gigFiltersProvider);
    final result = await showModalBottomSheet<GigFilters>(
      context: context,
      isScrollControlled: true,
      builder: (_) => GigFilterSheet(initialFilters: filters),
    );
    if (result != null) {
      await ref.read(gigsProvider.notifier).updateFilters(result);
    }
  }
}

class _EmptyState extends StatelessWidget {
  const _EmptyState();

  @override
  Widget build(BuildContext context) {
    return Center(
      child: Padding(
        padding: const EdgeInsets.all(24),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            Icon(Icons.work_outline, size: 56, color: Colors.grey.shade500),
            const SizedBox(height: 12),
            const Text('No gigs found. Try adjusting the filters or check back later.'),
          ],
        ),
      ),
    );
  }
}
