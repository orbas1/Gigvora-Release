import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../models/dispute.dart';
import '../repositories/freelance_repository.dart';

class DisputesNotifier extends AutoDisposeAsyncNotifier<List<Dispute>> {
  @override
  Future<List<Dispute>> build() async {
    final repository = ref.watch(freelanceRepositoryProvider);
    return repository.fetchDisputes();
  }

  Future<void> refresh() async {
    state = const AsyncLoading();
    state = await AsyncValue.guard(() async {
      final repository = ref.read(freelanceRepositoryProvider);
      return repository.fetchDisputes();
    });
  }

  Future<void> openDispute({
    required String subject,
    required String referenceType,
    required int referenceId,
    String? message,
  }) async {
    final repository = ref.read(freelanceRepositoryProvider);
    final dispute = await repository.openDispute(
      subject: subject,
      referenceType: referenceType,
      referenceId: referenceId,
      message: message,
    );
    final current = state.value ?? [];
    state = AsyncData([dispute, ...current]);
  }
}

final disputesProvider = AutoDisposeAsyncNotifierProvider<DisputesNotifier, List<Dispute>>(
  DisputesNotifier.new,
);
