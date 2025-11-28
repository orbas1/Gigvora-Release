import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../models/escrow.dart';
import '../services/escrow_service.dart';

class EscrowNotifier extends StateNotifier<AsyncValue<List<Escrow>>> {
  EscrowNotifier(this.service) : super(const AsyncValue.loading());
  final EscrowService service;

  Future<void> load() async {
    state = const AsyncValue.loading();
    try {
      state = AsyncValue.data(await service.fetchEscrows());
    } catch (e, st) {
      state = AsyncValue.error(e, st);
    }
  }
}
