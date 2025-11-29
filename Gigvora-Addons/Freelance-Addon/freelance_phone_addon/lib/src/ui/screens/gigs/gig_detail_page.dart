import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../../../models/gig.dart';
import '../../../state/core_providers.dart';
import '../../../state/gig_provider.dart';
import '../../theme/mobile_theme.dart';

class GigDetailPage extends ConsumerWidget {
  const GigDetailPage({super.key, required this.gigId});

  final int gigId;

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final gigState = ref.watch(gigDetailsProvider(gigId));

    return Scaffold(
      appBar: AppBar(title: const Text('Gig Details')),
      body: gigState.when(
        data: (gig) => _GigDetailView(gig: gig),
        error: (error, _) => Center(child: Text('Unable to load gig: $error')),
        loading: () => const Center(child: CircularProgressIndicator()),
      ),
      floatingActionButton: gigState.hasValue
          ? FloatingActionButton.extended(
              onPressed: () => _toggleFavourite(context, ref, gigState.value!),
              icon: Icon(gigState.value!.isFavourite ? Icons.favorite : Icons.favorite_border),
              label: Text(gigState.value!.isFavourite ? 'Saved' : 'Save'),
            )
          : null,
    );
  }

  Future<void> _toggleFavourite(BuildContext context, WidgetRef ref, Gig gig) async {
    try {
      await ref.read(freelanceRepositoryProvider).toggleFavourite(id: gig.id, type: 'gig');
      await ref.read(gigsProvider.notifier).refresh();
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text(gig.isFavourite ? 'Removed from favourites' : 'Saved to favourites')),
      );
    } catch (error) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Unable to update favourite: $error')),
      );
    }
  }
}

class _GigDetailView extends StatelessWidget {
  const _GigDetailView({required this.gig});

  final Gig gig;

  @override
  Widget build(BuildContext context) {
    return SingleChildScrollView(
      padding: const EdgeInsets.all(16),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          if (gig.attachments.files.isNotEmpty)
            ClipRRect(
              borderRadius: BorderRadius.circular(12),
              child: Image.network(gig.attachments.files.first.filePath, fit: BoxFit.cover),
            ),
          const SizedBox(height: 16),
          Text(gig.title, style: Theme.of(context).textTheme.headlineSmall),
          const SizedBox(height: 8),
          Row(
            children: [
              Icon(Icons.star, color: FreelanceMobileTheme.success(context)),
              const SizedBox(width: 4),
              Text('${gig.rating ?? 0} (${gig.reviews ?? 0} reviews)'),
            ],
          ),
          const SizedBox(height: 12),
          Text(gig.description, style: Theme.of(context).textTheme.bodyMedium),
          const SizedBox(height: 16),
          if (gig.address != null)
            Row(
              children: [
                const Icon(Icons.location_on_outlined),
                const SizedBox(width: 6),
                Text(gig.address!),
              ],
            ),
          const SizedBox(height: 16),
          Row(
            children: [
              const Icon(Icons.attach_money_outlined),
              const SizedBox(width: 6),
              Text('Starts at \\$${gig.price.toStringAsFixed(0)}',
                  style: Theme.of(context).textTheme.titleMedium),
            ],
          ),
          const SizedBox(height: 24),
          if (gig.user != null)
            ListTile(
              contentPadding: EdgeInsets.zero,
              leading: CircleAvatar(
                backgroundImage: gig.user!.avatar != null ? NetworkImage(gig.user!.avatar!) : null,
                child: gig.user!.avatar == null ? const Icon(Icons.person_outline) : null,
              ),
              title: Text(gig.user!.name),
              subtitle: Text(gig.user!.location ?? 'Freelancer'),
            ),
        ],
      ),
    );
  }
}
