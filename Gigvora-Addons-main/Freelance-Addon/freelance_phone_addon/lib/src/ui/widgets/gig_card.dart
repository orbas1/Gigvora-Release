import 'package:flutter/material.dart';

import '../../models/gig.dart';
import '../theme/mobile_theme.dart';

class GigCard extends StatelessWidget {
  const GigCard({
    super.key,
    required this.gig,
    required this.onTap,
    required this.onFavourite,
  });

  final Gig gig;
  final VoidCallback onTap;
  final VoidCallback onFavourite;

  @override
  Widget build(BuildContext context) {
    final image = gig.attachments.files.isNotEmpty ? gig.attachments.files.first.filePath : null;
    final colorScheme = Theme.of(context).colorScheme;

    return Card(
      elevation: 2,
      shape: FreelanceMobileTheme.cardShape(),
      child: InkWell(
        borderRadius: BorderRadius.circular(FreelanceMobileTheme.cardRadius),
        onTap: onTap,
        child: Row(
          children: [
            Container(
              width: 110,
              height: 120,
              decoration: BoxDecoration(
                borderRadius: const BorderRadius.only(
                  topLeft: Radius.circular(FreelanceMobileTheme.cardRadius),
                  bottomLeft: Radius.circular(FreelanceMobileTheme.cardRadius),
                ),
                image: image != null
                    ? DecorationImage(image: NetworkImage(image), fit: BoxFit.cover)
                    : null,
                color: FreelanceMobileTheme.surfaceVariant(context),
              ),
              child: Align(
                alignment: Alignment.topRight,
                child: IconButton(
                  icon: gig.isFavourite
                      ? Icon(Icons.favorite, color: colorScheme.error)
                      : const Icon(Icons.favorite_border),
                  onPressed: onFavourite,
                ),
              ),
            ),
            Expanded(
              child: Padding(
                padding: const EdgeInsets.all(12),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      gig.title,
                      maxLines: 2,
                      overflow: TextOverflow.ellipsis,
                      style: Theme.of(context).textTheme.titleMedium,
                    ),
                    const SizedBox(height: 4),
                    if (gig.user != null)
                      Row(
                        children: [
                          CircleAvatar(
                            backgroundImage:
                                gig.user!.avatar != null ? NetworkImage(gig.user!.avatar!) : null,
                            child: gig.user!.avatar == null ? const Icon(Icons.person_outline) : null,
                          ),
                          const SizedBox(width: 8),
                          Text(gig.user!.name, style: Theme.of(context).textTheme.bodyMedium),
                        ],
                      ),
                    const SizedBox(height: 6),
                    Text(
                      gig.description,
                      maxLines: 2,
                      overflow: TextOverflow.ellipsis,
                      style: Theme.of(context).textTheme.bodySmall,
                    ),
                    const SizedBox(height: 8),
                    Row(
                      children: [
                        Icon(Icons.star, size: 16, color: FreelanceMobileTheme.success(context)),
                        const SizedBox(width: 4),
                        Text('${gig.rating?.toStringAsFixed(1) ?? '0.0'} (${gig.reviews ?? 0} reviews)'),
                        const Spacer(),
                        Text(
                          '\$${gig.price.toStringAsFixed(0)}',
                          style: Theme.of(context)
                              .textTheme
                              .titleMedium
                              ?.copyWith(fontWeight: FontWeight.bold),
                        ),
                      ],
                    ),
                  ],
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
