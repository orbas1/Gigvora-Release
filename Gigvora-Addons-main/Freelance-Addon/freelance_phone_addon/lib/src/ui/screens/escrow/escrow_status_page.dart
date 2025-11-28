import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../../../state/escrow_provider.dart';

class EscrowStatusPage extends ConsumerWidget {
  const EscrowStatusPage({super.key});

  static const routeName = '/freelance/escrow';

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final escrowState = ref.watch(escrowProvider);

    return Scaffold(
      appBar: AppBar(title: const Text('Escrow status')),
      body: escrowState.when(
        data: (items) => items.isEmpty
            ? const _EmptyEscrow()
            : ListView.separated(
                padding: const EdgeInsets.all(12),
                itemBuilder: (context, index) {
                  final escrow = items[index];
                  return ListTile(
                    leading: const Icon(Icons.account_balance_wallet_outlined),
                    title: Text('Escrow #${escrow.id} – ${escrow.status}'),
                    subtitle: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text('Total: ${escrow.currency} ${escrow.amount.toStringAsFixed(2)}'),
                        Text('Released: ${escrow.currency} ${escrow.releasedAmount.toStringAsFixed(2)}'),
                        Text('Pending: ${escrow.currency} ${escrow.pendingAmount.toStringAsFixed(2)}'),
                        if (escrow.reference != null) Text('Reference: ${escrow.reference}'),
                      ],
                    ),
                  );
                },
                separatorBuilder: (_, __) => const SizedBox(height: 8),
                itemCount: items.length,
              ),
        error: (error, _) => Center(child: Text('Failed to load escrow: $error')),
        loading: () => const Center(child: CircularProgressIndicator()),
      ),
    );
  }
}

class _EmptyEscrow extends StatelessWidget {
  const _EmptyEscrow();

  @override
  Widget build(BuildContext context) {
    return Center(
      child: Padding(
        padding: const EdgeInsets.all(24),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            Icon(Icons.lock_outline, size: 56, color: Colors.grey.shade500),
            const SizedBox(height: 12),
            const Text('No escrow has been opened yet.'),
          ],
        ),
      ),
    );
  }
}
