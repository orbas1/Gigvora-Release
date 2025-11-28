import '../models/review.dart';
import 'api_client.dart';

class ReviewService {
  ReviewService(this.client);
  final ApiClient client;

  Future<void> submitReview(int contractId, Review review) {
    return client.post('/freelance/contracts/$contractId/reviews', review.toJson());
  }
}
