import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../models/escrow.dart';
import '../repositories/freelance_repository.dart';

class EscrowNotifier extends AutoDisposeAsyncNotifier<List<Escrow>> {
  @override
  Future<List<Escrow>> build() async {
    final repository = ref.watch(freelanceRepositoryProvider);
    return repository.fetchEscrows();
  }

  Future<void> refresh() async {
    state = const AsyncLoading();
    state = await AsyncValue.guard(() async {
      final repository = ref.read(freelanceRepositoryProvider);
      return repository.fetchEscrows();
    });
  }
}

final escrowProvider = AutoDisposeAsyncNotifierProvider<EscrowNotifier, List<Escrow>>(
  EscrowNotifier.new,
);
