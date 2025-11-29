import '../models/models.dart';
import 'api_client.dart';

class MarketplaceEscrowApi extends BaseApiService {
  MarketplaceEscrowApi({required super.baseUrl, super.tokenProvider, super.client});

  Future<Escrow> showByOrder(int orderId) async {
    final data = await get('/api/pro-network/marketplace/orders/$orderId/escrow');
    return Escrow.fromJson(data as Map<String, dynamic>);
  }

  Future<Escrow> openEscrow(int orderId, Map<String, dynamic> payload) async {
    final data = await post('/api/pro-network/marketplace/orders/$orderId/escrow/open', data: payload);
    return Escrow.fromJson(data as Map<String, dynamic>);
  }

  Future<Escrow> releaseEscrow(int escrowId, Map<String, dynamic> payload) async {
    final data = await post('/api/pro-network/marketplace/escrow/$escrowId/release', data: payload);
    return Escrow.fromJson(data as Map<String, dynamic>);
  }

  Future<Escrow> refundEscrow(int escrowId, Map<String, dynamic> payload) async {
    final data = await post('/api/pro-network/marketplace/escrow/$escrowId/refund', data: payload);
    return Escrow.fromJson(data as Map<String, dynamic>);
  }
}

class DisputesApi extends BaseApiService {
  DisputesApi({required super.baseUrl, super.tokenProvider, super.client});

  Future<Dispute> openDispute(int orderId, Map<String, dynamic> payload) async {
    final data = await post('/api/pro-network/marketplace/orders/$orderId/disputes', data: payload);
    return Dispute.fromJson(data as Map<String, dynamic>);
  }

  Future<Dispute> showDispute(int disputeId) async {
    final data = await get('/api/pro-network/marketplace/disputes/$disputeId');
    return Dispute.fromJson(data as Map<String, dynamic>);
  }

  Future<Dispute> replyToDispute(int disputeId, Map<String, dynamic> payload) async {
    final data = await post('/api/pro-network/marketplace/disputes/$disputeId/reply', data: payload);
    return Dispute.fromJson(data as Map<String, dynamic>);
  }

  Future<Dispute> resolveDispute(int disputeId, Map<String, dynamic> payload) async {
    final data = await post('/api/pro-network/marketplace/disputes/$disputeId/resolve', data: payload);
    return Dispute.fromJson(data as Map<String, dynamic>);
  }
}
