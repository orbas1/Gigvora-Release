import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../models/escrow_action.dart';
import '../repositories/freelance_repository.dart';

final escrowActionsProvider = FutureProvider.autoDispose<List<EscrowAction>>((ref) {
  final repository = ref.watch(freelanceRepositoryProvider);
  return repository.fetchEscrowActions();
});
