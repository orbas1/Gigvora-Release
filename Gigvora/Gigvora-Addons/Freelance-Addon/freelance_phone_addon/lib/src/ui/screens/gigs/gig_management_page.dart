import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../../../state/gig_management_provider.dart';
import '../../widgets/section_card.dart';

class GigManagementPage extends ConsumerWidget {
  const GigManagementPage({super.key, required this.gigId});

  static const routeName = '/freelance/gig/management';

  final int gigId;

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    if (gigId == 0) {
      return Scaffold(
        appBar: AppBar(title: const Text('Gig management')),
        body: const Center(
          child: Padding(
            padding: EdgeInsets.all(16),
            child: Text('Provide a gigId when navigating to see timeline, packages, and reviews.'),
          ),
        ),
      );
    }

    final management = ref.watch(gigManagementProvider(gigId));
    return Scaffold(
      appBar: AppBar(title: const Text('Gig management')),
      body: management.when(
        data: (data) => ListView(
          padding: const EdgeInsets.all(16),
          children: [
            Text(data.title, style: Theme.of(context).textTheme.headlineSmall),
            SectionCard(
              title: 'Timeline',
              child: Column(
                children: data.timeline
                    .map((item) => ListTile(
                          leading: const Icon(Icons.event_outlined),
                          title: Text(item.title),
                          subtitle: Text(item.description ?? ''),
                          trailing: Text(item.occurredAt?.toLocal().toString().split(' ').first ?? ''),
                        ))
                    .toList(),
              ),
            ),
            SectionCard(
              title: 'FAQ',
              child: Column(
                children: data.faqs
                    .map((faq) => ListTile(
                          leading: const Icon(Icons.help_outline),
                          title: Text(faq.question),
                          subtitle: Text(faq.answer),
                        ))
                    .toList(),
              ),
            ),
            SectionCard(
              title: 'Add-ons',
              child: Column(
                children: data.addons
                    .map((addon) => ListTile(
                          leading: const Icon(Icons.add_box_outlined),
                          title: Text(addon.title),
                          trailing: Text(addon.price.toStringAsFixed(2)),
                        ))
                    .toList(),
              ),
            ),
            SectionCard(
              title: 'Packages',
              child: Column(
                children: data.packages
                    .map((pkg) => ListTile(
                          leading: const Icon(Icons.inventory_outlined),
                          title: Text(pkg.name),
                          subtitle: Text('Delivery: ${pkg.deliveryTime} days'),
                          trailing: Text(pkg.price.toStringAsFixed(2)),
                        ))
                    .toList(),
              ),
            ),
            SectionCard(
              title: 'Requirements',
              child: Column(
                children: data.requirements
                    .map((req) => ListTile(
                          leading: const Icon(Icons.assignment_turned_in_outlined),
                          title: Text(req.prompt),
                        ))
                    .toList(),
              ),
            ),
            SectionCard(
              title: 'Change requests',
              child: Column(
                children: data.changes
                    .map((change) => ListTile(
                          leading: const Icon(Icons.change_circle_outlined),
                          title: Text(change.requester),
                          subtitle: Text(change.notes),
                          trailing: Text(change.status),
                        ))
                    .toList(),
              ),
            ),
            SectionCard(
              title: 'Reviews',
              child: Column(
                children: data.reviews
                    .map((review) => ListTile(
                          leading: const Icon(Icons.reviews_outlined),
                          title: Text('${review.rating} / 5'),
                          subtitle: Text(review.comment ?? ''),
                          trailing: Text(review.author),
                        ))
                    .toList(),
              ),
            ),
          ],
        ),
        error: (error, _) => Center(child: Text('Could not load gig management: $error')),
        loading: () => const Center(child: CircularProgressIndicator()),
      ),
    );
  }
}
