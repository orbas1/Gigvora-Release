import 'package:equatable/equatable.dart';

import 'certification.dart';
import 'education_entry.dart';
import 'profile_portfolio.dart';
import 'profile_review.dart';

class UserProfile extends Equatable {
  const UserProfile({
    required this.id,
    required this.name,
    this.avatar,
    this.userType,
    this.location,
    this.rating,
    this.ratingCount,
    this.freelancerTags = const <String>[],
    this.gigTags = const <String>[],
    this.skills = const <String>[],
    this.portfolios = const <ProfilePortfolio>[],
    this.educations = const <EducationEntry>[],
    this.certifications = const <Certification>[],
    this.reviews = const <ProfileReview>[],
  });

  final int id;
  final String name;
  final String? avatar;
  final String? userType;
  final String? location;
  final double? rating;
  final int? ratingCount;
  final List<String> freelancerTags;
  final List<String> gigTags;
  final List<String> skills;
  final List<ProfilePortfolio> portfolios;
  final List<EducationEntry> educations;
  final List<Certification> certifications;
  final List<ProfileReview> reviews;

  factory UserProfile.fromJson(Map<String, dynamic>? json) {
    if (json == null) {
      return const UserProfile(id: 0, name: '');
    }
    return UserProfile(
      id: json['id'] is int ? json['id'] : int.tryParse(json['id'].toString()) ?? 0,
      name: json['name']?.toString() ?? json['auther']?.toString() ?? '',
      avatar: json['avatar']?.toString() ?? json['user_avatar']?.toString(),
      userType: json['user_type']?.toString(),
      location: json['address']?.toString() ?? json['location']?.toString(),
      rating: json['rating'] is num
          ? (json['rating'] as num).toDouble()
          : double.tryParse(json['rating']?.toString() ?? ''),
      ratingCount: json['ratings_count'] is int
          ? json['ratings_count']
          : int.tryParse(json['ratings_count']?.toString() ?? ''),
      freelancerTags: (json['freelancer_tags'] as List?)?.map((e) => e.toString()).toList() ??
          (json['tags'] as List?)?.map((e) => e.toString()).toList() ??
          const <String>[],
      gigTags: (json['gig_tags'] as List?)?.map((e) => e.toString()).toList() ?? const <String>[],
      skills: (json['skills'] as List?)?.map((e) => e.toString()).toList() ??
          (json['skill_tags'] as List?)?.map((e) => e.toString()).toList() ??
          const <String>[],
      portfolios: (json['portfolios'] as List?)
              ?.map((e) => ProfilePortfolio.fromJson(e as Map<String, dynamic>?))
              .toList() ??
          const <ProfilePortfolio>[],
      educations: (json['educations'] as List?)
              ?.map((e) => EducationEntry.fromJson(e as Map<String, dynamic>?))
              .toList() ??
          const <EducationEntry>[],
      certifications: (json['certifications'] as List?)
              ?.map((e) => Certification.fromJson(e as Map<String, dynamic>?))
              .toList() ??
          const <Certification>[],
      reviews: (json['reviews'] as List?)
              ?.map((e) => ProfileReview.fromJson(e as Map<String, dynamic>?))
              .toList() ??
          const <ProfileReview>[],
    );
  }

  Map<String, dynamic> toJson() => {
        'id': id,
        'name': name,
        'avatar': avatar,
        'user_type': userType,
        'address': location,
        'rating': rating,
        'ratings_count': ratingCount,
        'freelancer_tags': freelancerTags,
        'gig_tags': gigTags,
        'skills': skills,
        'portfolios': portfolios.map((p) => p.toJson()).toList(),
        'educations': educations.map((e) => e.toJson()).toList(),
        'certifications': certifications.map((c) => c.toJson()).toList(),
        'reviews': reviews.map((r) => r.toJson()).toList(),
      };

  @override
  List<Object?> get props => [
        id,
        name,
        avatar,
        userType,
        location,
        rating,
        ratingCount,
        freelancerTags,
        gigTags,
        skills,
        portfolios,
        educations,
        certifications,
        reviews,
      ];
}
